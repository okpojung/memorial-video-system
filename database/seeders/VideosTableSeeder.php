<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VideosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Video::create([
            'title' =>'추모영상',
            'name' =>'추모영상',
            'format' =>'mp4',
            'thumbnail_url' =>'thumbnail/mvs_thumbnail.jpg',
            'playtime_seconds' =>'198',
            'playtime_string' =>'3:18',
            'video_url' =>'mvs/mvs.mp4',
            'created_at' =>now(),
            'updated_at' =>now()
        ]);
    }
}
