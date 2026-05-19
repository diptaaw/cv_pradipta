<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $experiences = [

        [
            'year' => '2025 — PRESENT',

            'title' =>
            'Staff PSDM · HIMA Multimedia Broadcasting PENS',

            'description' =>
            'Contributed to student development programs and organizational activities through team coordination, recruitment support, and collaborative event planning.',

            'tags' => [
                'Leadership',
                'Team Coordination'
            ]
        ]

    ];



    $projects = [

        [
            'image' =>
            'images/projects/wildlife.png',

            'title' =>
            'Interactive Wildlife Park',

            'description' =>
            'An educational wildlife park built in Unity featuring interactive systems, dynamic weather, NPC behavior, and immersive environment exploration.',

            'tags' => [
                'Unity',
                'C#',
                'Game Environment'
            ]
        ]

    ];



    return view(
        'home',
        compact('experiences', 'projects')
    );

});



Route::get('/projects', function () {

    return view('projects');

});