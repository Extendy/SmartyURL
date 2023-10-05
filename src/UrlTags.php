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
}
