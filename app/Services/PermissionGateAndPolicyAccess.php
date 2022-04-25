<?php

namespace App\Services;
use Illuminate\Support\Facades\Gate;

class PermissionGateAndPolicyAccess {

    public function setGateAndPolicyAccess(){
        $this->defineSongPolicy();
        $this->defineAlbumPolicy();
        $this->definePlaylistPolicy();
        $this->defineGenrePolicy();
        $this->defineUserPolicy();
        $this->defineSlidePolicy();
        $this->defineSetupAccountPolicy();
        $this->defineRolePolicy();
    }

    public function defineSongPolicy(){
        Gate::define('list_song', 'App\Policies\SongPolicy@view');
        Gate::define('add_song', 'App\Policies\SongPolicy@create');
        Gate::define('edit_song', 'App\Policies\SongPolicy@update');
        Gate::define('delete_song', 'App\Policies\SongPolicy@delete');
    }

    public function defineAlbumPolicy(){
        Gate::define('list_album', 'App\Policies\AlbumPolicy@view');
        Gate::define('add_album', 'App\Policies\AlbumPolicy@create');
        Gate::define('edit_album', 'App\Policies\AlbumPolicy@update');
        Gate::define('delete_album', 'App\Policies\AlbumPolicy@delete');
    }

    public function defineGenrePolicy(){
        Gate::define('list_genre', 'App\Policies\GenrePolicy@view');
        Gate::define('add_genre', 'App\Policies\GenrePolicy@create');
        Gate::define('edit_genre', 'App\Policies\GenrePolicy@update');
        Gate::define('delete_genre', 'App\Policies\GenrePolicy@delete');
    }

    public function defineUserPolicy(){
        Gate::define('list_user', 'App\Policies\UserPolicy@view');
        Gate::define('add_user', 'App\Policies\UserPolicy@create');
        Gate::define('edit_user', 'App\Policies\UserPolicy@update');
        Gate::define('delete_user', 'App\Policies\UserPolicy@delete');
    }

    public function definePlaylistPolicy(){
        Gate::define('list_playlist', 'App\Policies\PlaylistPolicy@view');
        Gate::define('add_playlist', 'App\Policies\PlaylistPolicy@create');
        Gate::define('edit_playlist', 'App\Policies\PlaylistPolicy@update');
        Gate::define('delete_playlist', 'App\Policies\PlaylistPolicy@delete');
    }

    public function defineSlidePolicy(){
        Gate::define('list_slide', 'App\Policies\SlidePolicy@view');
        Gate::define('add_slide', 'App\Policies\SlidePolicy@create');
        Gate::define('edit_slide', 'App\Policies\SlidePolicy@update');
        Gate::define('delete_slide', 'App\Policies\SlidePolicy@delete');
    }

    public function defineSetupAccountPolicy(){
        Gate::define('add_setup-account', function(){
            return auth()->user()->checkPermissionAccess('add_setup-account');
        });
        Gate::define('edit_setup-account', function(){
            return auth()->user()->checkPermissionAccess('edit_setup-account');
        });
    }

    public function defineRolePolicy(){
        Gate::define('list_role', 'App\Policies\RolePolicy@view');
        Gate::define('add_role', 'App\Policies\RolePolicy@create');
        Gate::define('edit_role', 'App\Policies\RolePolicy@update');
        Gate::define('delete_role', 'App\Policies\RolePolicy@delete');
    }
}