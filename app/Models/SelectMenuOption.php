<?php

namespace App\Models;

class SelectMenuOption extends AppModel
{
    protected $fillable = ['option','latin_option','is_selected','select_menu_id'];

    protected $hidden = ['created_at','updated_at'];

    public function selectMenu()
    {
        return $this->belongsTo(SelectMenu::class);
    }
}
