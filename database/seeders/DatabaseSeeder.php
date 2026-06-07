<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Tag;
use App\Models\Experience;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $superAdminRole = Role::firstOrCreate([
            'slug' => 'super-admin'
        ], [
            'name' => 'Super Admin'
        ]);

        $adminRole = Role::firstOrCreate([
            'slug' => 'admin'
        ], [
            'name' => 'Admin'
        ]);

        // 2. Seed default Super Admin
        $defaultAdmin = User::firstOrCreate([
            'email' => 'wicaksonodipta@gmail.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('03173008'),
            'is_admin' => true,
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);
        
        // Make sure it is assigned the correct role_id and is_active if it already existed
        $defaultAdmin->update([
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);

        // 3. Seed some mock Tags
        $tagLeadership = Tag::firstOrCreate(['slug' => 'leadership'], ['name' => 'Leadership']);
        $tagCoordination = Tag::firstOrCreate(['slug' => 'team-coordination'], ['name' => 'Team Coordination']);
        $tagUnity = Tag::firstOrCreate(['slug' => 'unity'], ['name' => 'Unity']);
        $tagCSharp = Tag::firstOrCreate(['slug' => 'c#'], ['name' => 'C#']);
        $tagPhotography = Tag::firstOrCreate(['slug' => 'photography'], ['name' => 'Photography']);

        // 4. Seed mock Experiences
        if (Experience::count() === 0) {
            $exp1 = Experience::create([
                'title' => 'Staff PSDM',
                'organization' => 'HIMA Multimedia Broadcasting PENS',
                'year' => '2025 — PRESENT',
                'description' => 'Contributed to student development programs and organizational activities through team coordination, recruitment support, and collaborative event planning.',
                'featured' => true,
                'is_published' => true,
                'position' => 1,
            ]);
            $exp1->tags()->sync([$tagLeadership->id, $tagCoordination->id]);

            $exp2 = Experience::create([
                'title' => 'Multimedia Intern',
                'organization' => 'PENS Creative Media',
                'year' => '2024',
                'description' => 'Assisted in videography, live-streaming events, photography editing, and designing marketing content.',
                'featured' => true,
                'is_published' => true,
                'position' => 2,
            ]);
            $exp2->tags()->sync([$tagPhotography->id]);
        }

        // 5. Seed mock Projects
        if (Project::count() === 0) {
            $proj1 = Project::create([
                'title' => 'Interactive Wildlife Park',
                'thumbnail' => 'images/projects/wildlife.png',
                'description' => 'An educational wildlife park built in Unity featuring interactive systems, dynamic weather, NPC behavior, and immersive environment exploration.',
                'featured' => true,
                'is_published' => true,
                'position' => 1,
                'year' => '2025',
                'category' => 'Game Development',
            ]);
            $proj1->tags()->sync([$tagUnity->id, $tagCSharp->id]);

            $proj2 = Project::create([
                'title' => 'Creative Portrait Showcase',
                'thumbnail' => 'images/projects/wildlife.png', // Fallback thumbnail path
                'description' => 'A photography project focused on compositional lighting and creative studio setups for client branding.',
                'featured' => true,
                'is_published' => true,
                'position' => 2,
                'year' => '2024',
                'category' => 'Photography',
            ]);
            $proj2->tags()->sync([$tagPhotography->id]);
        }

        // 6. Seed About Section
        if (\App\Models\AboutSection::count() === 0) {
            \App\Models\AboutSection::create([
                'headline' => 'Pradipta Adicandra Wicaksono',
                'subheadline' => 'Multimedia & Broadcasting Engineer',
                'short_intro' => 'Exploring the intersection of visuals, storytelling, and digital experiences.',
                'paragraphs' => [
                    "I'm a Multimedia Broadcasting student at PENS (EEPIS) with a strong interest in visual storytelling, creative production, and digital media. I enjoy transforming ideas into engaging visual experiences through photography, videography, live streaming, and design.",
                    "Over the past few years, I've worked on various creative and organizational projects, from commercial photography and content production to multimedia events and student organizations. These experiences helped me develop not only technical skills, but also adaptability, communication, and collaborative event planning in fast-paced production environments.",
                    "I'm especially interested in the creative process behind media production: how visuals, lighting, composition, and storytelling can shape emotions and audience experience."
                ],
                'profile_image' => 'images/ui/avatar.png',
                'is_published' => true,
            ]);
        }

        // 7. Seed Site Settings for Social Links
        $settings = [
            'social_github' => 'https://github.com',
            'social_instagram' => 'https://instagram.com',
            'social_linkedin' => 'https://linkedin.com',
            'social_email' => 'wicaksonodipta@gmail.com',
        ];

        foreach ($settings as $key => $value) {
            \App\Models\SiteSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // 8. Seed Notifications
        if (\App\Models\Notification::count() === 0) {
            \App\Models\Notification::create([
                'type' => 'project_created',
                'title' => "Added Interactive Wildlife Park",
                'description' => "New Unity project featuring dynamic weather, NPC systems and wildlife interactions.",
                'reference_type' => 'project',
                'reference_id' => 1,
                'is_pinned' => true,
                'is_read' => false,
            ]);
            \App\Models\Notification::create([
                'type' => 'experience_created',
                'title' => "Added Staff PSDM Experience",
                'description' => "Added organizational experience section.",
                'reference_type' => 'experience',
                'reference_id' => 1,
                'is_pinned' => false,
                'is_read' => false,
            ]);
            \App\Models\Notification::create([
                'type' => 'settings_updated',
                'title' => "Updated Homepage Animations",
                'description' => "Improved celestial background system.",
                'is_pinned' => false,
                'is_read' => false,
            ]);
        }
    }
}
