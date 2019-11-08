<?php


namespace App\Discourse\Models\API;


class Topic
{
    public $id; //int
    public $title; //String
    public $fancy_title; //String
    public $posts_count; //int
    public $reply_count; //int
    public $highest_post_number; //int
    public $created_at; //Date
    public $last_posted_at; //Date
    public $unseen; //boolean
    public $pinned; //boolean
    public $visible; //boolean
    public $closed; //boolean
    public $tags = []; //array(String)
    public $views; //int
    public $like_count; //int
    public $last_poster_username; //String
    public $category_id; //int
    public $has_accepted_answer; //boolean
    public $vote_count; //int

    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    public function fill(array $data)
    {
        $this->id                       = $data['id'];
        $this->title                    = $data['title'];
        $this->fancy_title              = $data['fancy_title'];
        $this->posts_count              = $data['posts_count'];
        $this->reply_count              = $data['reply_count'];
        $this->highest_post_number      = $data['highest_post_number'];
        $this->created_at               = $data['created_at'];
        $this->last_posted_at           = $data['last_posted_at'];
        $this->unseen                   = $data['unseen'];
        $this->pinned                   = $data['pinned'];
        $this->visible                  = $data['visible'];
        $this->closed                   = $data['closed'];
        $this->tags                     = $data['tags'];
        $this->views                    = $data['views'];
        $this->like_count               = $data['like_count'];
        $this->last_poster_username     = $data['last_poster_username'];
        $this->category_id              = $data['category_id'];
        $this->has_accepted_answer      = $data['has_accepted_answer'];
        $this->vote_count               = $data['vote_count'];
    }
}
