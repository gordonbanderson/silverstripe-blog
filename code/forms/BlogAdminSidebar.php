<?php

class BlogAdminSidebar extends FieldGroup
{
    /**
     * @return bool
     */
    public function isOpen()
    {
        $sidebar = Cookie::get('blog-admin-sidebar');
        error_log('T0: sidebar is *: ' . $sidebar .'*');
        error_log('T1: sidebar == 1: ' . ($sidebar == 1));
        error_log('T2: sidebar === 1: ' . ($sidebar === 1));
        error_log('T3: is_null($sidebar): ' . is_null($sidebar));
        error_log('T4: $sidebar == null: ' . ($sidebar == null));
        error_log('T5: $sidebar === null: ' . ($sidebar === null));

        // 0 is evaluated as null for PHP < 5.6, so add a special case
        if ($sidebar === 0) {
            return false;
        }

        if ($sidebar == 1 || is_null($sidebar)) {
            return true;
        }

        return false;
    }
}
