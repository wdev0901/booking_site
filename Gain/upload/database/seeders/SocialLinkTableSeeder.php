<?php
namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;
class SocialLinkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SocialLink::query()->insert([
            [
                'social_link_value' => 'facebook',
                'social_link_name' => 'https://facebook.com'
            ],
            [
                'social_link_value' => 'instagram',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'linkedin',
                'social_link_name' => 'https://linkedin.com',
            ],
            [
                'social_link_value' => 'twitter',
                'social_link_name' => 'https://twitter.com',
            ],
            [
                'social_link_value' => 'google_plus',
                'social_link_name' => null
            ],
            [
                'social_link_value' => 'youtube',
                'social_link_name' => 'https://youtube.com',
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
