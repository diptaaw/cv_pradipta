import json
import os
import sqlite3
from urllib.parse import urlparse, unquote

import psycopg2
from psycopg2.extras import execute_values, Json

THIS_DIR = os.path.dirname(os.path.abspath(__file__))
SQLITE_PATH = os.path.join(THIS_DIR, 'database', 'database.sqlite')

TABLE_ORDER = [
    'roles',
    'users',
    'experiences',
    'projects',
    'about_sections',
    'social_links',
    'site_settings',
    'resumes',
    'tags',
    'experience_tags',
    'project_tags',
    'media',
    'activity_logs',
    'resume_files',
    'portfolio_updates',
    'notifications',
    'sessions',
    'password_reset_tokens',
    'cache',
    'cache_locks',
    'jobs',
    'job_batches',
    'failed_jobs',
]

SKIP_TABLES = {'sqlite_sequence', 'migrations'}

JSON_COLUMNS = {
    'experiences': {'tags'},
    'projects': {'technologies', 'gallery_images'},
    'about_sections': {'paragraphs'},
}


def get_pg_connection():
    # Prefer a full connection URL if provided (DATABASE_URL or DB_URL)
    database_url = os.getenv('DATABASE_URL') or os.getenv('DB_URL')
    if database_url:
        return psycopg2.connect(database_url)

    # Fallback to individual DB_* env vars with sensible defaults
    return psycopg2.connect(
        host=os.getenv('DB_HOST', '127.0.0.1'),
        port=os.getenv('DB_PORT', '5432'),
        dbname=os.getenv('DB_DATABASE', 'postgres'),
        user=os.getenv('DB_USERNAME', 'postgres'),
        password=os.getenv('DB_PASSWORD'),
        sslmode=os.getenv('DB_SSLMODE', 'require'),
    )


def maybe_json(value, table, column):
    if value is None:
        return None
    if column in JSON_COLUMNS.get(table, set()):
        if isinstance(value, str):
            try:
                return json.loads(value)
            except json.JSONDecodeError:
                return value
    if isinstance(value, str):
        text = value.strip()
        if text.startswith('{') or text.startswith('['):
            try:
                return json.loads(text)
            except json.JSONDecodeError:
                return value
    return value


def get_columns(cur, table):
    cur.execute(f"PRAGMA table_info('{table}')")
    rows = cur.fetchall()
    return [row[1] for row in rows]


def copy_table(sqlite_cur, pg_cur, table, columns):
    quoted_columns = ', '.join([f'"{col}"' for col in columns])
    # execute_values expects a single %s placeholder for the VALUES list
    # Use ON CONFLICT DO NOTHING to skip rows that already exist in the target
    insert_sql = f'INSERT INTO "{table}" ({quoted_columns}) VALUES %s ON CONFLICT DO NOTHING'

    sqlite_cur.execute(f'SELECT {quoted_columns} FROM "{table}"')
    rows = sqlite_cur.fetchall()
    if not rows:
        print(f'Skipping {table} (no rows)')
        return
    # Detect boolean columns in target Postgres table so we can convert 0/1 -> True/False
    boolean_cols = get_boolean_columns(pg_cur, table)

    cleaned_rows = []
    for row in rows:
        cleaned = []
        for col, value in zip(columns, row):
            val = maybe_json(value, table, col)

            # Preserve NULLs
            if val is None:
                cleaned.append(None)
                continue

            # Convert integers 0/1 to booleans for Postgres boolean columns
            if col in boolean_cols:
                if isinstance(val, (int, float)):
                    cleaned.append(bool(val))
                    continue
                s = str(val).strip()
                if s in ('0', '1'):
                    cleaned.append(s == '1')
                    continue
                if s.lower() in ('true', 'false'):
                    cleaned.append(s.lower() == 'true')
                    continue
                # Fallback: try numeric conversion
                try:
                    num = int(s)
                    cleaned.append(bool(num))
                    continue
                except Exception:
                    pass

            # For known JSON columns, wrap dict/list in psycopg2.extras.Json
            if col in JSON_COLUMNS.get(table, set()):
                if isinstance(val, (dict, list)):
                    cleaned.append(Json(val))
                    continue
                else:
                    # attempt to parse JSON strings to native types
                    try:
                        parsed = json.loads(val) if isinstance(val, str) else val
                        if isinstance(parsed, (dict, list)):
                            cleaned.append(Json(parsed))
                            continue
                        else:
                            cleaned.append(val)
                            continue
                    except Exception:
                        cleaned.append(val)
                        continue

            cleaned.append(val)
        cleaned_rows.append(tuple(cleaned))

    # execute_values will substitute the single %s with a parenthesized list of rows
    execute_values(pg_cur, insert_sql, cleaned_rows)
    print(f'Inserted {len(rows)} rows into {table}')


def set_sequence(pg_cur, table):
    pg_cur.execute("SELECT column_name FROM information_schema.columns WHERE table_name = %s AND column_default LIKE 'nextval(%%'", (table,))
    serial_columns = [row[0] for row in pg_cur.fetchall()]
    for column in serial_columns:
        pg_cur.execute(f'SELECT MAX("{column}") FROM "{table}"')
        max_value = pg_cur.fetchone()[0]
        if max_value is not None:
            pg_cur.execute(f"SELECT setval(pg_get_serial_sequence(%s, %s), %s, true)", (table, column, max_value))
            print(f'Set sequence for {table}.{column} to {max_value}')


def get_boolean_columns(pg_cur, table):
    """Return a set of boolean column names for the given Postgres table."""
    try:
        pg_cur.execute(
            "SELECT column_name FROM information_schema.columns WHERE table_name = %s AND (data_type = 'boolean' OR udt_name = 'bool')",
            (table,)
        )
        return {row[0] for row in pg_cur.fetchall()}
    except Exception:
        return set()


def main():
    if not os.path.exists(SQLITE_PATH):
        raise FileNotFoundError(f'SQLite database not found at {SQLITE_PATH}')

    sqlite_conn = sqlite3.connect(SQLITE_PATH)
    sqlite_conn.row_factory = sqlite3.Row
    sqlite_cur = sqlite_conn.cursor()

    pg_conn = get_pg_connection()
    pg_cur = pg_conn.cursor()

    try:
        for table in TABLE_ORDER:
            if table in SKIP_TABLES:
                continue

            columns = get_columns(sqlite_cur, table)
            if not columns:
                print(f'No columns found for table {table}, skipping.')
                continue

            copy_table(sqlite_cur, pg_cur, table, columns)
            pg_conn.commit()

        for table in TABLE_ORDER:
            if table in SKIP_TABLES:
                continue
            set_sequence(pg_cur, table)
        pg_conn.commit()

    finally:
        sqlite_conn.close()
        pg_conn.close()

    print('SQLite to PostgreSQL migration complete.')


if __name__ == '__main__':
    main()
