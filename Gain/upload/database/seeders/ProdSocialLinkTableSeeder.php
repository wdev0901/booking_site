<?php


namespace Database\Seeders;


use App\Models\SocialLink;
use Illuminate\Database\Seeder;


class ProdSocialLinkTableSeeder extends Seeder
{
    public function run()
    {
        SocialLink::query()->truncate();

        SocialLink::query()->insert([
            [
                'social_link_value' => 'facebook',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'instagram',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'linkedin',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'twitter',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'google_plus',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'youtube',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'pinterest',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'tumblr',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'flickr',
                'social_link_name' => null
            ],
        ]);
    }
}