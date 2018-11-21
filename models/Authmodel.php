<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use \Illuminate\Database\Eloquent\Model as Eloquent;

class Authmodel extends Eloquent {
    protected $table = "login";

    public static function get_password($email)
    {
		$q= self::where('email',$email)
				->orWhere('username', $email)
				->get()
				->take(1)
				->toArray();

		return !empty($q) ? $q : false;
    }
}