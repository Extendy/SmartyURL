<?php

namespace Extendy\Smartyurl;

use App\Models\UrlTagsModel;

class UrlTags
{
    /**
     * Generate URL tags Cloud for user
     *
     * @return array|false|string
     */
    public function getUserUrlTagsCloud(int $userid, ?int $limit = null, ?bool $jsonreturn = true)
    {
        $UrlTagsModel      = new UrlTagsModel();
        $shared_tags_cloud = setting('Smartyurl.urltags_shared_between_users');
        $sqlreturn         = $UrlTagsModel->getTagsCloud($userid, $shared_tags_cloud, $limit);
        $tagscloud         = $sqlreturn;

        return $jsonreturn ? json_encode($tagscloud) : $tagscloud;
    }

    // This function try to insert tags if not already exists it urltags db table
    public function tryInsertTags(array $tags)
    {
        $inserted_ids      = [];
        $UrlTagsModel      = new UrlTagsModel();
        $shared_tags_cloud = setting('Smartyurl.urltags_shared_between_users');

        foreach ($tags as $tag) {
            $tag_exists_result = $UrlTagsModel->findTagNo($tag, user_id(), $shared_tags_cloud);
            if ($tag_exists_result > 0) {
                // tag exists no need to add it
                // no need to add it
            } else {
                // tag not exists i will add it
                $tagdata = [
                    'tag_name'    => esc($tag),
                    'tag_user_id' => user_id(),
                ];
                $UrlTagsModel->insert($tagdata);
                $inserted_ids[] = ['value' => "{$tag}", 'tag_id' => $UrlTagsModel->getInsertID()];
            }
        }

        return $inserted_ids;
    }
}
