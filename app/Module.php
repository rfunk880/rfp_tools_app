<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module
{
    /* Users PermiSSIONS */

    const USERS_VIEW = 'users_view';
    const USERS_ADD = 'users_create';
    const USERS_DELETE = 'users_delete';
    const USERS_EDIT = 'users_edit';
    const USERS_ADD_EDIT = [self::USERS_ADD, self::USERS_EDIT, self::USERS_VIEW];

    /* Contacts Permissions */
    const CONTACTS_VIEW = 'contacts_view';
    const CONTACTS_ADD = 'contacts_create';
    const CONTACTS_EDIT = 'contacts_edit';
    const CONTACTS_DELETE = 'contacts_delete';

    /* COMPANIES Permissions */
    const COMPANIES_VIEW = 'companies_view';
    const COMPANIES_ADD = 'companies_create';
    const COMPANIES_EDIT = 'companies_edit';
    const COMPANIES_DELETE = 'companies_delete';
    const COMPANIES_BULK = 'companies_bulk';
    
    /* Projects Permissions */
    const PROJECTS_VIEW = 'projects_view';
    const PROJECTS_ADD = 'projects_create';
    const PROJECTS_EDIT = 'projects_edit';
    const PROJECTS_DELETE = 'projects_delete';
    const PROJECTS_BULK = 'projects_bulk';
    
    /* Messages Permissions */
    const MESSAGES_VIEW = 'messages_view';
    const MESSAGES_ADD = 'messages_create';
    const MESSAGES_EDIT = 'messages_edit';
    const MESSAGES_DELETE = 'messages_delete';

    /* Messages Permissions */
    const CALLLOGS_VIEW = 'calllogs_view';
    const CALLLOGS_ADD = 'calllogs_create';
    const CALLLOGS_EDIT = 'calllogs_edit';
    const CALLLOGS_DELETE = 'calllogs_delete';
    
    /* Facilities Permissions */
    const FACILITIES_VIEW = 'facilities_view';
    const FACILITIES_ADD = 'facilities_create';
    const FACILITIES_EDIT = 'facilities_edit';
    const FACILITIES_DELETE = 'facilities_delete';
    
    /* Reports Permissions */
    const REPORTS_VIEW = 'reports_view';
}
