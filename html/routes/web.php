<?php

use App\Http\Controllers\Auth2FaController;
use App\Http\Controllers\Dashboard\AttrezzatureController;
use App\Http\Controllers\Dashboard\AziendaController;
use App\Http\Controllers\Dashboard\AziendaLiteController;
use App\Http\Controllers\Dashboard\CarburanteController;
use App\Http\Controllers\Dashboard\ChecklistController;
use App\Http\Controllers\Dashboard\ChecklistTemplateController;
use App\Http\Controllers\Dashboard\ChecklistTemplateNodeController;
use App\Http\Controllers\Dashboard\ClienteController;
use App\Http\Controllers\Dashboard\CommessaAutorizzazioniController;
use App\Http\Controllers\Dashboard\CommessaController;
use App\Http\Controllers\Dashboard\CommessaLogController;
use App\Http\Controllers\Dashboard\CommessaNodeController;
use App\Http\Controllers\Dashboard\CommessaRapportinoController;
use App\Http\Controllers\Dashboard\CommessaTemplateController;
use App\Http\Controllers\Dashboard\CommessaTemplateNodeController;
use App\Http\Controllers\Dashboard\CommessaUtilsController;
use App\Http\Controllers\Dashboard\ControlloController;
use App\Http\Controllers\Dashboard\DeviceController;
use App\Http\Controllers\Dashboard\EventoController;
use App\Http\Controllers\Dashboard\FatturaController;
use App\Http\Controllers\Dashboard\GruppoController;
use App\Http\Controllers\Dashboard\HumanActivityController;
use App\Http\Controllers\Dashboard\Inail\ModOt23_2024Controller;
use App\Http\Controllers\Dashboard\Inail\ModOt23Controller;
use App\Http\Controllers\Dashboard\InfosituataController;
use App\Http\Controllers\Dashboard\InfosituataPublicController;
use App\Http\Controllers\Dashboard\ItemController;
use App\Http\Controllers\Dashboard\IvaController;
use App\Http\Controllers\Dashboard\ManutenzioneController;
use App\Http\Controllers\Dashboard\ManutenzioneDettaglioController;
use App\Http\Controllers\Dashboard\MaterialiController;
use App\Http\Controllers\Dashboard\MessaggioController;
use App\Http\Controllers\Dashboard\MezziController;
use App\Http\Controllers\Dashboard\MicroformazioneController;
use App\Http\Controllers\Dashboard\ModuloAziendaAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloChecklistAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloClienteAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloCommesseAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloComunicazioniAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloFatturaAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloHAMAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloInfosituataAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloPrevenzioneAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloRapportiniAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloTasksAutorizzazioniController;
use App\Http\Controllers\Dashboard\ModuloTimbratureAutorizzazioniController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\PackageController;
use App\Http\Controllers\Dashboard\RapportiniController;
use App\Http\Controllers\Dashboard\RicambiController;
use App\Http\Controllers\Dashboard\RisorseController;
use App\Http\Controllers\Dashboard\RisorsePublicController;
use App\Http\Controllers\Dashboard\S3Controller;
use App\Http\Controllers\Dashboard\ScadenzarioController;
use App\Http\Controllers\Dashboard\SedeController;
use App\Http\Controllers\Dashboard\SmsController;
use App\Http\Controllers\Dashboard\SquadraController;
use App\Http\Controllers\Dashboard\SquadraItemController;
use App\Http\Controllers\Dashboard\TaskAutorizzazioniController;
use App\Http\Controllers\Dashboard\TaskController;
use App\Http\Controllers\Dashboard\TaskNodeController;
use App\Http\Controllers\Dashboard\TaskTemplateController;
use App\Http\Controllers\Dashboard\TaskTemplateNodeController;
use App\Http\Controllers\Dashboard\TicketController;
use App\Http\Controllers\Dashboard\TimbratureController;
use App\Http\Controllers\Dashboard\TimbraturePermessiController;
use App\Http\Controllers\Dashboard\TipologiaScadenzaController;
use App\Http\Controllers\Dashboard\TopicController;
use App\Http\Controllers\Dashboard\UploadController;
use App\Http\Controllers\Dashboard\UploadPublicController;
use App\Http\Controllers\Dashboard\UploadS3Controller;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\WhatsappController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Helpers\ImageController;
use App\Http\Controllers\PrivacyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


Auth::routes(['register' => false]);

Route::get('/2fa', [Auth2FaController::class, 'index'])->middleware(['auth'])->name('auth2fa.index');
Route::post('/2fa/check', [Auth2FaController::class, 'check'])->middleware(['auth'])->name('auth2fa.check');

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth','can:2fa'])->name('dashboard.index');
Route::get('/home', [DashboardController::class, 'index'])->middleware(['auth','can:2fa'])->name('dashboard.index');

// Azienda
Route::resource('azienda', AziendaController::class)->middleware(['auth','can:2fa','can:enter-dashboard']);

// Azienda lite
Route::resource('azienda-lite', AziendaLiteController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Sede
Route::resource('sede', SedeController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Gruppo
Route::post('/dashboard/gruppo/{gruppo_id}/users', [GruppoController::class, 'users'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('gruppo.users');;
Route::delete('/dashboard/gruppo/{gruppo_id}/user/{user_id}', [GruppoController::class, 'destroyUser'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('gruppo.destroy-user');
Route::resource('gruppo', GruppoController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// User
Route::get('/dashboard/user/export', [UserController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('user.export');
Route::get('/dashboard/user/password', [UserController::class, 'password'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('user.password');
Route::resource('user', UserController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Attrezzature
Route::get('/dashboard/attrezzature/export', [AttrezzatureController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('attrezzature.export');
Route::resource('attrezzature', AttrezzatureController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Materiali
Route::get('/dashboard/materiali/export', [MaterialiController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('materiali.export');
Route::resource('materiali', MaterialiController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Mezzi
Route::get('/dashboard/mezzi/export', [MezziController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('mezzi.export');
Route::resource('mezzi', MezziController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Risorse
Route::get('/dashboard/risorse/export', [RisorseController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('risorse.export');
Route::resource('risorse', RisorseController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::get('/dashboard/public/risorse/{id}', [RisorsePublicController::class, 'show'])->name('risorse-public.show');

// Package
Route::resource('package', PackageController::class)->middleware(['auth','can:enter-dashboard','can:2fa','can:privacy-accepted']);
Route::get('/dashboard/error/package', [PackageController::class, 'error'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('package.error');

// Scadenzario
Route::get('/calendar', [ScadenzarioController::class, 'calendar'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('scadenzario.calendar');
Route::get('/dashboard/scadenzario/{id_commessa}/commessa', [ScadenzarioController::class, 'commessa'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('scadenzario.commessa');
Route::get('/dashboard/scadenzario/{id_scadenza}/commessa/show', [ScadenzarioController::class, 'showCommessa'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('scadenzario.show-commessa');
Route::post('/dashboard/scadenzario/{id_commessa}/commessa', [ScadenzarioController::class, 'storeCommessa'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('scadenzario.store-commessa');
Route::delete('/dashboard/scadenzario/{id_scadenza}/commessa', [ScadenzarioController::class, 'destroyCommessa'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('scadenzario.destroy-commessa');
Route::post('/dashboard/scadenzario/tipologia/{infosituata_moduli_details_id}/{azienda_id}/store', [ScadenzarioController::class, 'storeNewTipologiaScadenza'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('scadenzario.store-new-tipologia-scadenza');
Route::put('/dashboard/scadenzario/{id}/check', [ScadenzarioController::class, 'check'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('scadenzario.check');
Route::resource('scadenzario', ScadenzarioController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Messaggio
Route::get('/dashboard/messaggio/user/{id}/show', [MessaggioController::class, 'showUser'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('messaggio.show-user');
Route::resource('messaggio', MessaggioController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Topic
Route::get('/dashboard/topic/load-other/messages', [TopicController::class, 'loadOtherMessages'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('topic.load-other-messages');
Route::get('/dashboard/topic/{id}/messages', [TopicController::class, 'messages'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('topic.messages');
Route::post('/dashboard/topic/store/{id}/message', [TopicController::class, 'storeMessage'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('topic.store-message');
Route::resource('topic', TopicController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Eventi
Route::resource('evento', EventoController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Rapportini
Route::get('/dashboard/rapportini/{id}/print', [RapportiniController::class, 'print'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('rapportini.print');
Route::resource('rapportini', RapportiniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Checklist
Route::get('/dashboard/checklist/{id}/print', [ChecklistController::class, 'print'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('checklist.print');
Route::get('/dashboard/checklist/commessa/{id_commessa}', [ChecklistController::class, 'commessa'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('checklist.commessa');
Route::get('/dashboard/checklist/{id_template}/render', [ChecklistController::class, 'render'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('checklist.render');
Route::resource('checklist', ChecklistController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Checklist template
Route::get('/dashboard/checklist-tpl/{id}/duplicate', [ChecklistTemplateController::class, 'duplicate'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('checklist-template.duplicate');
Route::get('/dashboard/checklist-tpl/{id}/tree/refresh', [ChecklistTemplateController::class, 'refreshTree'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('checklist-template.refresh-tree');
Route::resource('checklist-template', ChecklistTemplateController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

Route::get('/dashboard/checklist-tpl-node/{id}/move/{versus}', [ChecklistTemplateNodeController::class, 'move'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('checklist-template-node.move');
Route::resource('checklist-template-node', ChecklistTemplateNodeController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Task
Route::get('/dashboard/task/assegnati', [TaskController::class, 'assegnati'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task.assegnati');
Route::get('/dashboard/task/{id}/avvisi', [TaskController::class, 'avvisi'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task.avvisi');
Route::get('/dashboard/task/{id}/allegati', [TaskController::class, 'allegati'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task.allegati');
Route::get('/dashboard/task/{id}/fasi', [TaskController::class, 'fasiSelect2'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task.fasi-select2');
Route::get('/dashboard/task/{id}/tree/refresh', [TaskController::class, 'refreshTree'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task.refresh-tree');
Route::post('/dashboard/task/{id}/print', [TaskController::class, 'print'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task.print');
Route::get('/dashboard/task/select2', [TaskController::class, 'select2'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task.select2');
Route::resource('task', TaskController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Task Node
Route::get('/dashboard/task-node/{id}/started', [TaskNodeController::class, 'started'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.started');
Route::get('/dashboard/task-node/{id}/completed', [TaskNodeController::class, 'completed'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.completed');
Route::get('/dashboard/task-node/{id}/note', [TaskNodeController::class, 'note'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.note');
Route::get('/dashboard/task-node/{id}/move/{versus}', [TaskNodeController::class, 'move'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.move');
Route::get('/dashboard/task-node/{id}/item/create', [TaskNodeController::class, 'newItem'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.new-item');
Route::get('/dashboard/task-node/{id}/status/change', [TaskNodeController::class, 'statusChange'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.status-change');
Route::get('/dashboard/task-node/{id}/logs', [TaskNodeController::class, 'logs'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.logs');
Route::post('/dashboard/task-node/{id}/extra-field-copy', [TaskNodeController::class, 'extrafieldCopy'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.extra-field-copy');
Route::post('/dashboard/task-node/{id}/massive-copy', [TaskNodeController::class, 'massiveCopy'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.massive-copy');
Route::post('/dashboard/task-node/{id}/store/{squadra_id}/squadra', [TaskNodeController::class, 'squadra'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.squadra');
Route::post('/dashboard/task-node/{id}/store/log', [TaskNodeController::class, 'storeLog'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.store-log');
Route::post('/dashboard/task-node/{id}/update/extra', [TaskNodeController::class, 'extra'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-node.extra');
Route::resource('task-node', TaskNodeController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Task Template
Route::get('/dashboard/task-tpl/select2', [TaskTemplateController::class, 'select2'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-template.select2');
Route::get('/dashboard/task-tpl/{id}/duplicate', [TaskTemplateController::class, 'duplicate'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-template.duplicate');
Route::get('/dashboard/task-tpl/{id}/tree/refresh', [TaskTemplateController::class, 'refreshTree'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-template.refresh-tree');
Route::resource('task-template', TaskTemplateController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Task Template Node
Route::get('/dashboard/task-tpl-node/{id}/move/{versus}', [TaskTemplateNodeController::class, 'move'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-template-node.move');
Route::resource('task-template-node', TaskTemplateNodeController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Task Autorizzazioni
Route::post('/dashboard/task-autorizzazioni/copy', [TaskAutorizzazioniController::class, 'copy'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('task-autorizzazioni.copy');
Route::resource('task-autorizzazioni', TaskAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Commessa
Route::get('/dashboard/commessa/{format}/qr', [CommessaController::class, 'qr'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.qr');
Route::get('/dashboard/commessa/{id}/mark-in', [CommessaController::class, 'markIn'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.mark-in');
Route::get('/dashboard/commessa/{id}/mark-out', [CommessaController::class, 'markOut'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.mark-out');
Route::post('/dashboard/commessa/{id}/mark', [CommessaController::class, 'storeMark'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.store-mark');
Route::get('/dashboard/commessa/{id}/avvisi', [CommessaController::class, 'avvisi'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.avvisi');
Route::get('/dashboard/commessa/{id}/allegati', [CommessaController::class, 'allegati'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.allegati');
Route::get('/dashboard/commessa/{id}/fasi', [CommessaController::class, 'fasiSelect2'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.fasi-select2');
Route::get('/dashboard/commessa/clienti', [CommessaController::class, 'clienti'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.clienti');
Route::get('/dashboard/commessa/{id}/tree/refresh', [CommessaController::class, 'refreshTree'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.refresh-tree');
Route::get('/dashboard/commessa/{id}/gantt', [CommessaController::class, 'gantt'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.gantt');
Route::get('/dashboard/commessa/{id}/gantt20', [CommessaController::class, 'gantt20'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.gantt20');
Route::post('/dashboard/commessa/notifications', [CommessaController::class, 'notifications'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.notifications');
Route::post('/dashboard/commessa/{id}/calculate/costi', [CommessaController::class, 'calculateCostiConsuntivi'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.calculate-costi-consuntivi');
Route::post('/dashboard/commessa/{id}/print', [CommessaController::class, 'print'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.print');
Route::get('/dashboard/commessa/select2', [CommessaController::class, 'select2'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa.select2');
Route::resource('commessa', CommessaController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Commessa Fase
Route::get('/dashboard/commessa-node/{id}/note', [CommessaNodeController::class, 'note'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.note');
Route::get('/dashboard/commessa-node/{id}/move/{versus}', [CommessaNodeController::class, 'move'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.move');
Route::get('/dashboard/commessa-node/{id}/item/create', [CommessaNodeController::class, 'newItem'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.new-item');
Route::get('/dashboard/commessa-node/{id}/status/change', [CommessaNodeController::class, 'statusChange'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.status-change');
Route::get('/dashboard/commessa-node/{id}/logs', [CommessaNodeController::class, 'logs'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.logs');
Route::post('/dashboard/commessa-node/{id}/extra-field-copy', [CommessaNodeController::class, 'extrafieldCopy'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.extra-field-copy');
Route::post('/dashboard/commessa-node/{id}/massive-copy', [CommessaNodeController::class, 'massiveCopy'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.massive-copy');
Route::post('/dashboard/commessa-node/{id}/store/{squadra_id}/squadra', [CommessaNodeController::class, 'squadra'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.squadra');
Route::post('/dashboard/commessa-node/{id}/store/log', [CommessaNodeController::class, 'storeLog'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.store-log');
Route::post('/dashboard/commessa-node/{id}/update/extra', [CommessaNodeController::class, 'extra'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-node.extra');
Route::resource('commessa-node', CommessaNodeController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Commessa Log
Route::resource('commessa-log', CommessaLogController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Commessa Rapportino
Route::resource('commessa-rapportino', CommessaRapportinoController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Commessa Autorizzazioni
Route::post('/dashboard/commessa-autorizzazioni/copy', [CommessaAutorizzazioniController::class, 'copy'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-autorizzazioni.copy');
Route::resource('commessa-autorizzazioni', CommessaAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Commessa Utilities
Route::post('/dashboard/commessa-utils/scheduler-commesse', [CommessaUtilsController::class, 'showSchedulerCommesse'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-utils.show-scheduler-commesse');
Route::get('/dashboard/commessa-utils/scheduler-commesse', [CommessaUtilsController::class, 'schedulerCommesse'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-utils.scheduler-commesse');
Route::get('/dashboard/commessa-utils/map', [CommessaUtilsController::class, 'map'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-utils.map');
Route::get('/dashboard/commessa-utils/scheduler/select-items', [CommessaUtilsController::class, 'schedulerSelectItems'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-utils.scheduler-select-items');
Route::post('/dashboard/commessa-utils/scheduler', [CommessaUtilsController::class, 'showScheduler'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-utils.show-scheduler');
Route::get('/dashboard/commessa-utils/scheduler', [CommessaUtilsController::class, 'scheduler'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-utils.scheduler');
Route::get('/dashboard/commessa-utils/fasi/select2', [CommessaUtilsController::class, 'fasiSelect2'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-utils.fasi-select2');
Route::get('/dashboard/commessa-utils/sovrapposizioni', [CommessaUtilsController::class, 'sovrapposizioni'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-utils.sovrapposizioni');
Route::post('/dashboard/commessa-utils/sovrapposizioni', [CommessaUtilsController::class, 'sovrapposizioniGantt'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-utils.sovrapposizioni-gantt');

// Commessa Template
Route::get('/dashboard/commessa-template/select2', [CommessaTemplateController::class, 'select2'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-template.select2');
Route::get('/dashboard/commessa-template/{id}/duplicate', [CommessaTemplateController::class, 'duplicate'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-template.duplicate');
Route::get('/dashboard/commessa-template/{id}/tree/refresh', [CommessaTemplateController::class, 'refreshTree'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-template.refresh-tree');
Route::resource('commessa-template', CommessaTemplateController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Commessa Template Fase
Route::get('/dashboard/commessa-template-node/{id}/move/{versus}', [CommessaTemplateNodeController::class, 'move'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('commessa-template-node.move');
Route::resource('commessa-template-node', CommessaTemplateNodeController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Squadre
Route::get('/dashboard/squadra/search', [SquadraController::class, 'search'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('squadra.search');
Route::resource('squadra', SquadraController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Squadra item
Route::post('/dashboard/squadra-item/add', [SquadraItemController::class, 'add'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('squadra-item.add');
Route::resource('squadra-item', SquadraItemController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Clienti
Route::get('/dashboard/cliente/import', [ClienteController::class, 'import'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('cliente.import');
Route::get('/dashboard/cliente/import/export', [ClienteController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('cliente.export');
Route::get('/dashboard/cliente/import/cancel', [ClienteController::class, 'importCancel'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('cliente.import-cancel');
Route::get('/dashboard/cliente/import/do', [ClienteController::class, 'doImport'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('cliente.do-import');
Route::post('/dashboard/cliente/import/upload', [ClienteController::class, 'upload'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('cliente.upload');
Route::get('/dashboard/cliente/select2', [ClienteController::class, 'select2'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('cliente.select2');
Route::resource('cliente', ClienteController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Iva
Route::resource('iva', IvaController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Fattura
Route::resource('fattura', FatturaController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Notification
Route::get('/dashboard/notification/{id}/show', [NotificationController::class, 'show'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('notification.show');

// Whatsapp
Route::get('/dashboard/whatsapp/{id}/load-other/messages', [WhatsappController::class, 'loadOtherMessages'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('whatsapp.load-other-messages');
Route::get('/dashboard/whatsapp/{id}/chat', [WhatsappController::class, 'showChat'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('whatsapp.show-chat');
Route::get('/dashboard/whatsapp/{id}/messages', [WhatsappController::class, 'messages'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('whatsapp.messages');
Route::get('/dashboard/whatsapp/chat', [WhatsappController::class, 'chat'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('whatsapp.chat');
Route::get('/dashboard/whatsapp/storeMessage', [WhatsappController::class, 'storeMessage'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('whatsapp.store-message');
Route::post('/dashboard/whatsapp/send', [WhatsappController::class, 'send'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('whatsapp.send');
Route::resource('whatsapp', WhatsappController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// SMS
Route::get('/dashboard/sms/configure', [SmsController::class, 'configure'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('sms.configure');
Route::resource('sms', SmsController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Ticket
Route::get('/dashboard/ticket/{id}/attachment', [TicketController::class, 'attachment'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('ticket.attachment');
Route::post('/dashboard/ticket/ps', [TicketController::class, 'ps'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('ticket.ps');
Route::resource('ticket', TicketController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Tipologie scadenze
Route::resource('tipologia-scadenza', TipologiaScadenzaController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Infosituata
Route::get('/dashboard/infosituata/{md5_id}/check', [InfosituataController::class, 'check'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('infosituata.check');
Route::get('/dashboard/infosituata/{format}/qr', [InfosituataController::class, 'qr'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('infosituata.qr');
Route::get('/dashboard/infosituata/{utente_id}/log', [InfosituataController::class, 'log'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('infosituata.log');
Route::get('/dashboard/infosituata/export/{utente_id}/log', [InfosituataController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('infosituata.log');
Route::put('/dashboard/infosituata/{item_id}/visibility', [InfosituataController::class, 'visibility'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('infosituata.visibility');
Route::get('/dashboard/infosituata/{md5_id}/check/public', [InfosituataPublicController::class, 'check'])->name('infosituata-public.check');

// Humanactivity
Route::get('/dashboard/item/search', [ItemController::class, 'search'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('item.search');
Route::get('/dashboard/item/select2', [ItemController::class, 'select2'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('item.select2');
Route::resource('item', ItemController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Humanactivity
Route::resource('human-activity', HumanActivityController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Inail
Route::get('/dashboard/inail/modot23/analysis', [ModOt23Controller::class, 'analysis'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('mod-ot23.analysis');
Route::get('/dashboard/inail/modot23/{id}/pdf', [ModOt23Controller::class, 'pdf'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('mod-ot23.pdf');
Route::get('/dashboard/inail/modot23/export/{year}', [ModOt23Controller::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('mod-ot23.export');
Route::resource('mod-ot23', ModOt23Controller::Class)->middleware(['auth','can:2fa','can:privacy-accepted']);

Route::get('/dashboard/inail/modot23_2024/{id}/pdf', [ModOt23_2024Controller::class, 'pdf'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('mod-ot23_2024.pdf');
Route::get('/dashboard/inail/modot23_2024/export/{year}', [ModOt23_2024Controller::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('mod-ot23_2024.export');
Route::resource('mod-ot23_2024', ModOt23_2024Controller::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Device
Route::get('/dashboard/device/configuration',  [DeviceController::class, 'configuration'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('device.configuration');
Route::put('/dashboard/device/{id}/configuration',  [DeviceController::class, 'updateConfiguration'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('device.update-configuration');
Route::resource('device', DeviceController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Manutenzione
Route::get('/dashboard/manutenzione/{id}/dettagli', [ManutenzioneController::class, 'dettagli'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('manutenzione.dettagli');
Route::get('/dashboard/manutenzione/{id}/export', [ManutenzioneController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('manutenzione.export');
Route::resource('manutenzione', ManutenzioneController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('manutenzione-dettaglio', ManutenzioneDettaglioController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Controllo
Route::get('/dashboard/controllo/{id}/export', [ControlloController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('controllo.export');
Route::resource('controllo', ControlloController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Carburante
Route::get('/dashboard/carburante/{id}/export', [CarburanteController::class, 'export'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('carburante.export');
Route::resource('carburante', CarburanteController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Ricambi
Route::resource('ricambi', RicambiController::class)->middleware(['auth']);

// Upload
Route::get('/dashboard/attachments/{item_id}', [UploadController::class, 'attachments'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('upload.attachments');
Route::get('/dashboard/download/{md5_attachment_id}', [UploadController::class, 'download'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('upload.download');
Route::post('/dashboard/delete/{id}', [UploadController::class, 'delete'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('upload.delete');
Route::post('/dashboard/upload', [UploadController::class, 'upload'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('upload.upload');
Route::post('/dashboard/upload/{id}/visibility', [UploadController::class, 'visibility'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('upload.visibility');

// Upload S3
Route::get('/dashboard/upload-s3/modal', [UploadS3Controller::class, 'modal'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('upload-s3.modal');
Route::get('/dashboard/upload-s3/{reference_id}', [UploadS3Controller::class, 'attachments'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('upload-s3.attachments');
Route::post('/dashboard/upload-s3/{reference_id}/visibility/{flag}', [UploadS3Controller::class, 'visibility'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('upload-s3.visibility');
Route::post('/dashboard/upload-s3/{reference_id}/embedded/{flag}', [UploadS3Controller::class, 'embedded'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('upload-s3.embedded');
Route::resource('upload-s3', UploadS3Controller::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Link to S3
Route::get('/upl/{uuid}/get', [S3Controller::class, 'get'])->name('s3.get');

Route::get('/dashboard/download/{md5_attachment_id}/public', [UploadPublicController::class, 'download'])->name('upload-public.download');

// Timbrature
Route::get('/dashboard/timbrature/refresh/select/commesse', [TimbratureController::class, 'refreshSelectCommesse'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('timbrature.refresh-select-commesse');
Route::get('/dashboard/timbrature/mensili', [TimbratureController::class, 'mensili'])->middleware(['auth','can:2fa','can:privacy-accepted'])->name('timbrature.mensili');
Route::resource('timbrature', TimbratureController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Permessi
Route::resource('timbrature-permessi', TimbraturePermessiController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Privacy
Route::resource('privacy', PrivacyController::class)->middleware(['auth']);

// Autorizzazioni
Route::resource('mod-azi-aut', ModuloAziendaAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-inf-aut', ModuloInfosituataAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-tim-aut', ModuloTimbratureAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-comun-aut', ModuloComunicazioniAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-rap-aut', ModuloRapportiniAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-che-aut', ModuloChecklistAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-tas-aut', ModuloTasksAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-com-aut', ModuloCommesseAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-ham-aut', ModuloHAMAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-pre-aut', ModuloPrevenzioneAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-cli-aut', ModuloClienteAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);
Route::resource('mod-fat-aut', ModuloFatturaAutorizzazioniController::class)->middleware(['auth','can:2fa','can:privacy-accepted']);

// Microformazione
Route::get('/microformazione', [MicroformazioneController::class, 'index'])->name('microformazione.index');
Route::get('/microformazione/alcol', [MicroformazioneController::class, 'alcol'])->name('microformazione.alcol');
Route::get('/microformazione/manuale', [MicroformazioneController::class, 'manuale'])->name('microformazione.manuale');

// Image
Route::post('/image/resize', [ImageController::class, 'resize'])->name('image.resize');

Route::get('/apk', function () {
    $file_path = public_path('download/apk/InfosituataHAM.apk');
    return response()->file($file_path ,[
        'Content-Type'=>'application/vnd.android.package-archive',
        'Content-Disposition'=> 'attachment; filename="InfosituataHAM.apk"',
    ]);
});

Route::get('/dashboard/force/{user_id}/login', function($id){
    \Illuminate\Support\Facades\Auth::logout();
    Session::flush();

    $u = \App\Models\User::find($id);
    \Illuminate\Support\Facades\Auth::login($u);
    return redirect()->to('/');
})->middleware(['auth','can:enter-dashboard']);

// Force login
Route::get('/test', function(){

    /** Creo i clienti **/
    $materiali = \App\Models\Materiale::get();
    foreach ($materiali as $m) {
        $azienda_id = $m->azienda_id;
        $rs = trim(\Illuminate\Support\Str::title($m->extras2));
        $c = \App\Models\Cliente::where('azienda_id', $azienda_id)->where('rs', $rs)->first();
        if (!$c) {
            $c = new \App\Models\Cliente;
            $c->azienda_id = $azienda_id;
            $c->rs = $rs;
            $c->tipo_fattura = 'b2b';

            $c->id = \Illuminate\Support\Str::uuid();
            $c->save();
        }
    }

    $commesse = \App\Models\Commessa::whereNull('parent_id')->get();
    foreach ($commesse as $m) {
        $azienda_id = $m->azienda_id;
        $rs = trim(\Illuminate\Support\Str::title($m->cliente));
        $c = \App\Models\Cliente::where('azienda_id', $azienda_id)->where('rs', $rs)->first();
        if (!$c) {
            $c = new \App\Models\Cliente;
            $c->azienda_id = $azienda_id;
            $c->rs = $rs;
            $c->tipo_fattura = 'b2b';

            $c->id = \Illuminate\Support\Str::uuid();
            $c->save();
        }
    }

    /** Associo i clienti **/
    foreach ($materiali as $m) {
        $azienda_id = $m->azienda_id;
        $rs = trim(\Illuminate\Support\Str::title($m->extras2));
        $c = \App\Models\Cliente::where('azienda_id', $azienda_id)->where('rs', $rs)->first();

        $m->clienti_id = $c->id;
        $m->save();
    }

    foreach ($commesse as $m) {
        $azienda_id = $m->azienda_id;
        $rs = trim(\Illuminate\Support\Str::title($m->cliente));
        $c = \App\Models\Cliente::where('azienda_id', $azienda_id)->where('rs', $rs)->first();

        $m->clienti_id = $c->id;
        $m->save();
    }

})->middleware(['auth']);

Route::get('/ssh', function(){
    \Illuminate\Support\Facades\Artisan::call('migrate');
})->middleware(['auth']);

Route::get('/ssh/test', function(){
    \Illuminate\Support\Facades\Artisan::call('test:run');
})->middleware(['auth']);

Route::get('/whoiam', function(){
    return '(ID: '.auth()->user()->id.') ' .auth()->user()->name . ' ' . auth()->user()->email;
})->middleware(['auth']);

Route::get('/super/login/{id}', function($id){
    if (auth()->user()->id === 1) {
        Session::flush();

        $u = \App\Models\User::find($id);
        \Illuminate\Support\Facades\Auth::login($u);
        return redirect()->to('/');
    }
})->middleware(['auth']);


