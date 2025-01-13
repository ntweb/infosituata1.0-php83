<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
//        $this->app->bind('path.public', function() {
//            return base_path().DIRECTORY_SEPARATOR.'public_html';
//        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view)
        {
            $messages = headerUnreadMessage();
            $view->with('headerMessagesNumber', count($messages));
            $view->with('headerMessages', $messages);

            $topics = headerUnreadTopic();
            $view->with('headerTopicsNumber', count($topics));
            $view->with('headerTopics', $topics);

            $uncheckedPermessi = headerUncheckedPermessi();
            $view->with('uncheckedPermessi', $uncheckedPermessi);

            $notifications = headerNotifications();
            $view->with('headerNotificationsNumber', count($notifications));
            $view->with('headerNotifications', $notifications);
        });

        // Gates definitions
        Gate::define('enter-dashboard', function ($user) {
            return $user->superadmin;
        });

        Gate::define('can-create', function ($user) {
            return $user->superadmin || $user->azienda_id || $user->power_user;
        });

        Gate::define('privacy-accepted', function ($user) {
            return $user->privacy_fl_1 && $user->active == '1';
            // return $user->privacy_fl_1 && $user->privacy_fl_2 && $user->privacy_fl_4 && $user->privacy_fl_5;
        });

        Gate::define('2fa', function ($user) {
            if ($user->_2fa) {
                return !$user->_2fa_code;
            }

            return true;
        });

        Gate::define('is-superadmin', function ($user) {
            return $user->superadmin == '1';
        });

        Gate::define('is-poweruser', function ($user) {
            return $user->power_user == '1';
        });

        Gate::define('testing', function ($user) {
            $azienda_id = getAziendaId();
            if ($azienda_id == 1 || $azienda_id == 26)
                return true;

            return false;
        });

        Gate::define('can_create_gruppi', function ($user) {
            return $this->permissions('can_create_gruppi', $user);
        });

        Gate::define('can_create_sedi', function ($user) {
            return $this->permissions('can_create_sedi', $user);
        });

        Gate::define('can_create_utenti', function ($user) {
            return $this->permissions('can_create_utenti', $user);
        });

        Gate::define('can_create_mezzi', function ($user) {
            return $this->permissions('can_create_mezzi', $user);
        });

        Gate::define('can_create_manutenzione_mezzi', function ($user) {
            return $this->permissions('can_create_manutenzione_mezzi', $user);
        });

        Gate::define('can_create_controllo_mezzi', function ($user) {
            return $this->permissions('can_create_controllo_mezzi', $user);
        });

        Gate::define('can_create_sc_carburante_mezzi', function ($user) {
            return $this->permissions('can_create_sc_carburante_mezzi', $user);
        });

        Gate::define('can_create_attrezzature', function ($user) {
            return $this->permissions('can_create_attrezzature', $user);
        });

        Gate::define('can_create_manutenzione_attrezzature', function ($user) {
            return $this->permissions('can_create_manutenzione_attrezzature', $user);
        });

        Gate::define('can_create_controllo_attrezzature', function ($user) {
            return $this->permissions('can_create_controllo_attrezzature', $user);
        });

        Gate::define('can_create_materiali', function ($user) {
            return $this->permissions('can_create_materiali', $user);
        });

        Gate::define('can_create_risorse', function ($user) {
            return $this->permissions('can_create_risorse', $user);
        });

        Gate::define('can_create_tip_scadenza', function ($user) {
            return $this->permissions('can_create_tip_scadenza', $user);
        });

        Gate::define('can_create_eventi', function ($user) {
            return $this->permissions('can_create_eventi', $user);
        });

        Gate::define('can_create_messaggi', function ($user) {
            return $this->permissions('can_create_messaggi', $user);
        });

        Gate::define('can_create_topic', function ($user) {
            return $this->permissions('can_create_topic', $user);
        });

        Gate::define('can_create_sms', function ($user) {
            return $this->permissions('can_create_sms', $user);
        });

        Gate::define('can_create_whatsapp', function ($user) {
            return $this->permissions('can_create_whatsapp', $user);
        });

        Gate::define('can_create_template_checklist', function ($user) {
            return $this->permissions('can_create_template_checklist', $user);
        });

        Gate::define('can_create_tasks', function ($user) {
            return $this->permissions('can_create_tasks', $user);
        });

        Gate::define('can_create_tasks_template', function ($user) {
            return $this->permissions('can_create_tasks_template', $user);
        });

        Gate::define('can_create_commesse', function ($user) {
            return $this->permissions('can_create_commesse', $user);
        });

        Gate::define('can_create_commesse_template', function ($user) {
            return $this->permissions('can_create_commesse_template', $user);
        });

        Gate::define('can_create_commesse_squadre', function ($user) {
            return $this->permissions('can_create_commesse_squadre', $user);
        });

        Gate::define('can_show_commesse_utility', function ($user) {
            return $this->permissions('can_show_commesse_utility', $user);
        });

        Gate::define('can_create_ham', function ($user) {
            return $this->permissions('can_create_ham', $user);
        });

        Gate::define('can_create_ham_terminali', function ($user) {
            return $this->permissions('can_create_ham_terminali', $user);
        });

        Gate::define('can_create_mancati_infortuni_rspp', function ($user) {
            return $this->permissions('can_create_mancati_infortuni_rspp', $user);
        });

        Gate::define('can_show_commesse_utility', function ($user) {
            return $this->permissions('can_show_commesse_utility', $user);
        });

        Gate::define('can_create_mancati_infortuni_export', function ($user) {
            return $this->permissions('can_create_mancati_infortuni_export', $user);
        });

        Gate::define('can_create_clienti', function ($user) {
            return $this->permissions('can_create_clienti', $user);
        });

        Gate::define('can_create_iva', function ($user) {
            return $this->permissions('can_create_iva', $user);
        });

        Gate::define('is-timbrature-module-enabled', function ($user) {
            $azienda = getAziendaBySessionUser();
            return $azienda->module_timbrature == '1';
        });

        Gate::define('is-user-timbrature-module-enabled', function ($user) {
            $azienda = getAziendaBySessionUser();
            if ($azienda->module_timbrature == '1') {
                if ($user->superadmin || $user->power_user || !$user->utente_id)
                    return true;

                $permissions = json_decode($user->permissions, true);
                if ($permissions) {
                    return in_array('can_create_timbratura', $permissions);
                }
            }

            return false;
        });

        Gate::define('can-list-timbrature-module', function ($user) {
            $azienda = getAziendaBySessionUser();
            if ($azienda->module_timbrature == '1') {
                if ($user->superadmin || $user->power_user || !$user->utente_id)
                    return true;

                $permissions = json_decode($user->permissions, true);
                if ($permissions) {
                    return in_array('can_create_update_timbratura', $permissions);
                }
            }

            return false;
        });

        Gate::define('is-tasks-module-enabled', function ($user) {
            $azienda = getAziendaBySessionUser();
            if (!$azienda)
                return false;

            // Log::info($azienda);
            return $azienda->module_tasks == '1';
        });

        Gate::define('is-commesse-module-enabled', function ($user) {
            $azienda = getAziendaBySessionUser();
            return $azienda->module_commesse == '1';
        });

        Gate::define('is-rapportini-module-enabled', function ($user) {
            $azienda = getAziendaBySessionUser();
            return $azienda->module_rapportini == '1';
        });

        Gate::define('is-checklist-module-enabled', function ($user) {
            $azienda = getAziendaBySessionUser();
            return $azienda->module_checklist == '1';
        });

        Gate::define('is-sms-module-enabled', function ($user) {
            $azienda = getAziendaBySessionUser();
            if (!$azienda)
                return false;

            return $azienda->module_sms == '1';
        });

        Gate::define('is-whatsapp-module-enabled', function ($user) {
            $azienda = getAziendaBySessionUser();
            if (!$azienda)
                return false;

            return $azienda->module_whatsapp == '1';
        });

        /** Autorizzazioni commessa **/
        Gate::define('commessa_mod_anagrafica', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_mod_anagrafica', $user, $commessa);
        });

        Gate::define('commessa_mod_fasi', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_mod_fasi', $user, $commessa);
        });

        Gate::define('commessa_mod_extra_fields', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_mod_extra_fields', $user, $commessa);
        });

        Gate::define('commessa_update_extra_fields', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_update_extra_fields', $user, $commessa);
        });

        Gate::define('commessa_mod_autorizzazioni', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_mod_autorizzazioni', $user, $commessa);
        });

        Gate::define('commessa_mod_date', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_mod_date', $user, $commessa);
        });

        Gate::define('commessa_mod_costi', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_mod_costi', $user, $commessa);
        });

        Gate::define('commessa_mod_stato', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_mod_stato', $user, $commessa);
        });

        Gate::define('commessa_view_costi', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_view_costi', $user, $commessa);
        });

        Gate::define('avvisi_create', function($user, $commessa) {
            return $this->checkCommessaPermission('avvisi_create', $user, $commessa);
        });

        Gate::define('rapportini_create', function($user, $commessa) {
            return $this->checkCommessaPermission('rapportini_create', $user, $commessa);
        });

        Gate::define('risorse_create', function($user, $commessa) {
            return $this->checkCommessaPermission('risorse_create', $user, $commessa);
        });

        Gate::define('commessa_view_log', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_view_log', $user, $commessa);
        });

        Gate::define('risorse_create_log', function($user, $commessa) {
            return $this->checkCommessaPermission('risorse_create_log', $user, $commessa);
        });

        Gate::define('risorse_view_log', function($user, $commessa) {
            return $this->checkCommessaPermission('risorse_view_log', $user, $commessa);
        });

        Gate::define('commessa_notify_status', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_notify_status', $user, $commessa);
        });

        Gate::define('commessa_uploads', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_uploads', $user, $commessa);
        });

        Gate::define('commessa_print', function($user, $commessa) {
            return $this->checkCommessaPermission('commessa_print', $user, $commessa);
        });

        Gate::define('checklist_create', function($user, $commessa) {
            return $this->checkCommessaPermission('checklist_create', $user, $commessa);
        });

        Gate::define('can-update-checklist', function ($user, $checklist) {
            if ($user->superadmin || $user->azienda_id || $user->power_user)
                return true;

            if ($user->id == $checklist->users_id)
                return true;

            return false;
        });

        Gate::define('can-create-rapportini', function ($user, $controller) {
            return $this->permissions($controller, $user);
        });

        Gate::define('can-delete-rapportino', function ($user, $rapportino) {
            if ($user->superadmin || $user->azienda_id || $user->power_user)
                return true;

            return $rapportino->users_id === $user->id;
        });

        Gate::define('can-create-checklist', function ($user, $reference_controller) {
            return $this->permissions($reference_controller, $user);
        });

        Gate::define('can-delete-checklist', function ($user, $checklist) {
            if ($user->superadmin || $user->azienda_id || $user->power_user)
                return true;

            return $checklist->users_id === $user->id;
        });

        /** Autorizzazioni task **/
        Gate::define('task_mod_anagrafica', function($user, $task) {
            return $this->checkTaskPermission('task_mod_anagrafica', $user, $task);
        });

        Gate::define('task_mod_fasi', function($user, $task) {
            return $this->checkTaskPermission('task_mod_fasi', $user, $task);
        });

        Gate::define('task_mod_extra_fields', function($user, $task) {
            return $this->checkTaskPermission('task_mod_extra_fields', $user, $task);
        });

        Gate::define('task_update_extra_fields', function($user, $task) {
            return $this->checkTaskPermission('task_update_extra_fields', $user, $task);
        });

        Gate::define('task_mod_autorizzazioni', function($user, $task) {
            return $this->checkTaskPermission('task_mod_autorizzazioni', $user, $task);
        });

        Gate::define('task_mod_date', function($user, $task) {
            return $this->checkTaskPermission('task_mod_date', $user, $task);
        });

        Gate::define('task_mod_costi', function($user, $task) {
            return $this->checkTaskPermission('task_mod_costi', $user, $task);
        });

        Gate::define('task_mod_stato', function($user, $task) {
            return $this->checkTaskPermission('task_mod_stato', $user, $task);
        });

        Gate::define('task_view_costi', function($user, $task) {
            return $this->checkTaskPermission('task_view_costi', $user, $task);
        });

        Gate::define('task_view_log', function($user, $task) {
            return $this->checkTaskPermission('task_view_log', $user, $task);
        });

        Gate::define('task_notify_status', function($user, $task) {
            return $this->checkTaskPermission('task_notify_status', $user, $task);
        });

        Gate::define('task_uploads', function($user, $task) {
            if ($task->users_ids) {
                $ids = json_decode($task->users_ids);
                if (in_array($user->id, $ids))
                    return true;
            }
            return $this->checkTaskPermission('task_uploads', $user, $task->root);
        });

        Gate::define('task_print', function($user, $task) {
            return $this->checkTaskPermission('task_print', $user, $task);
        });

        /** Autorizzazioni allegati **/
        Gate::define('can_delete_s3_attachment', function($user, $attachment) {
            if ($user->superadmin || $user->azienda_id || $user->power_user) {
                return true;
            }

            return $attachment->users_id === $user->id;
        });
    }

    private function checkCommessaPermission($autorizzazione, $user, $commessa) {
        if ($user->superadmin || $user->azienda_id || $user->power_user) {
            return true;
        }

        // Log::info($autorizzazione);
        $auth = json_decode($commessa->auth, true);
        if ($auth) {
            if (isset($auth[$autorizzazione])) {
                return in_array($user->id, $auth[$autorizzazione]);
            }
        }
        return false;
    }

    private function checkTaskPermission($autorizzazione, $user, $task) {
        if ($user->superadmin || $user->azienda_id || $user->power_user) {
            return true;
        }

        if (Gate::allows('can_create_tasks', $user)) {
            return true;
        }

        // Log::info($autorizzazione);
        $auth = json_decode($task->auth, true);
        if ($auth) {
            if (isset($auth[$autorizzazione])) {
                return in_array($user->id, $auth[$autorizzazione]);
            }
        }
        return false;
    }

    private function permissions($permission, $user) {
        if ($user->superadmin || $user->azienda_id || $user->power_user)
            return true;

        // Log::info($permission);
        // Log::info($user->permissions);
        $permissions = json_decode($user->permissions, true);
        if ($permissions) {
            return in_array($permission, $permissions);
        }

        return false;
    }
}
