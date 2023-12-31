<?php

namespace Extendy\Smartyurl;

use App\Models\UrlTagsDataModel;
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
        // first of all we will get the user tag and put them on the first array.
        $user_tags = $UrlTagsModel->getTagsCloud($userid, false, $limit);
        // now  I will get all tags again but with shared tags is enabled
        $sqlreturn = [];
        if ($shared_tags_cloud) {
            $sqlreturn = $UrlTagsModel->getTagsCloud($userid, $shared_tags_cloud, $limit);
        }
        // Why I did 2 queries and 2 arrays?
        // I did this in order to check for duplicates among tags. If there
        // is a duplication, I prioritize using tags that are primarily associated with the user instead of other
        // users tags.
        // @TODO If you can do it without 2 db queries and just in 1 query please send PR.

        $all_tags_cloud = array_merge($user_tags, $sqlreturn);
        $tagscloud      = $this->removeDuplicateTags($all_tags_cloud);

        return $jsonreturn ? json_encode($tagscloud) : $tagscloud;
    }

    /**
     * This function returns the TagsCloud for the given $UrlId
     *
     * @param mixed $UrlId
     *
     * @return false|string json
     */
    public function getUrlTagsCloud($UrlId)
    {
        // @TODO may be while list urls page i will use thus but after covert $UrlId to array ???
        // the return cloud should be array like
        // [{"value":"tag1","tag_id":"3"},{"value":"tag2","tag_id":"27"},{"value":"tag3","tag_id":"24"}] ... etc

        $UrlTagsDataModel = new UrlTagsDataModel();
        $urlTags          = $UrlTagsDataModel->getUrlTags($UrlId);
        // I will get the tag names
        $tags = [];

        foreach ($urlTags as $tag) {
            $tags[] = $tag['tag_id'];
        }
        // I will try to get the Tag names
        if (count($tags) > 0) {
            // url has tags
            $UrlTagsModel = new UrlTagsModel();
            $tagscloud    = $UrlTagsModel->getTagInfoById($tags);
        } else {
            // url has no tags
            $tagscloud = [];
        }
        // define the final url tags cloud
        $urltagscloud = [];

        foreach ($tagscloud as $tag) {
            $urltagscloud[] = [
                'value'  => $tag->tag_name,
                'tag_id' => $tag->tag_id,
            ];
        }

        return json_encode($urltagscloud);
    }

    // This function try to insert tags if not already exists it urltags db table
    public function tryInsertTags(array $tags): array
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
                    'tag_name'    => $tag,
                    'tag_user_id' => user_id(),
                ];
                $UrlTagsModel->insert($tagdata);
                $inserted_ids[] = ['value' => "{$tag}", 'tag_id' => $UrlTagsModel->getInsertID()];
            }
        }

        return $inserted_ids;
    }

    /**
     * This function search for duplicates in the $tags[][tag_name]
     *      within your array and keep only the first occurrence of each unique tag_name
     *      sometimes when tags are shared between users may be they uses same names for thire tags
     *      so we need to make sure there is no duplicate names.
     *
     * @param mixed $tags
     *
     * @return array
     */
    protected function removeDuplicateTags($tags)
    {
        $uniqueTags   = []; // An array to store unique tag names
        $filteredTags = []; // An array to store the filtered tags

        foreach ($tags as $tag) {
            $tagName = $tag['tag_name'];

            // Check if the tag name is not already in the uniqueTags array
            if (! in_array($tagName, $uniqueTags, true)) {
                // Add the tag name to the uniqueTags array
                $uniqueTags[] = $tagName;

                // Add the whole tag to the filteredTags array
                $filteredTags[] = $tag;
            }
        }

        return $filteredTags;
    }
}
