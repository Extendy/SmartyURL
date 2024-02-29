<?php

namespace App\Controllers;

use App\Models\UrltagsModel;
use CodeIgniter\Controller;
use CodeIgniter\I18n\Time;

class Urltags extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        // permissions need to access this page is url.access
        if (! auth()->user()->can('url.access')) {
            return smarty_permission_error();
        }

        $urltagsModel = new UrltagsModel();
        $tags         = $urltagsModel->getTagsWithUrlCount();

        // Format the created_at datetime field
        foreach ($tags as &$tag) {
            // Convert to Time object
            $createdAt = Time::createFromFormat('Y-m-d H:i:s', $tag['created_at']);

            // Format as desired (e.g., 'F j, Y, g:i a')
            $tag['created_at'] = lang('Common.CreatedAt') . ' ' . $createdAt->format('F j, Y, g:i a');
        }

        $data['tags'] = $tags;

        return view(smarty_view('url/tags'), $data);
    }
}
