@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row"><div class="col-md-5">{{ __('Users List') }}</div><div class="col-md-3"><button class="btn btn-success btn-block" data-toggle="modal" data-target="#modal">Ajouter un utilisateur</button></div> </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


<table class="table table-bordered" id="UsersDatatable" data-url="{{route('userdatatable')}}">
  <thead>
    <tr>
      <th scope="col">S#</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Status</th>
      <th scope="col">Date</th>
      <th scope="col">View Locations</th>
      <th scope="col">Actions</th>

    </tr>
  </thead>

</table>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- / .modal -->
<div id="modal" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header ">
                <div class="modal-title text-md">Veuillez entrer les informations de l'utilisateur</div>
            </div>
            <div class="modal-body">
                <div class="col-md-12 text-center">
                    <small class="text-danger" id="error_field"></small>
                    <small class="text-success" id="success_field"></small>
                </div>
                <form action="" method="POST">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nom</label>
                        <div class="col-sm-10">
                            <input type="text" id="name" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Mot de passe</label>
                        <div class="col-sm-8">
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Confirmer le mot de passe</label>
                        <div class="col-sm-8 mt-2">
                            <input type="password" id="repassword" name="repassword" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rôle</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="role" id="role">
                                <option value="1">Super-utilisateur</option>
                                <option value="2">Gestionnaire</option>
                                <option value="3">Invité</option>
                            </select>
                        </div>
                        <label class="col-sm-2 col-form-label text-right">Statut</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="status" id="status">
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="save_user()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-edit" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header ">
                <div class="modal-title text-md">Veuillez modifier les informations de l'utilisateur à mettre à jour</div>
            </div>
            <div class="modal-body">
                <div class="col-md-12 text-center">
                    <small class="text-danger" id="e_error_field"></small>
                    <small class="text-success" id="e_success_field"></small>
                </div>
                <form action="" method="POST">
                    <input type="hidden" id="e_id" name="e_id">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nom</label>
                        <div class="col-sm-10">
                            <input type="text" id="e_name" name="e_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" id="e_email" name="e_email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Mot de passe</label>
                        <div class="col-sm-8">
                            <input type="password" id="e_password" name="e_password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Confirmer le mot de passe</label>
                        <div class="col-sm-8 mt-2">
                            <input type="password" id="e_repassword" name="e_repassword" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rôle</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="e_role" id="e_role">
                                <option value="" id="v_role">---Changer---</option>
                                <option value="1">Super-utilisateur</option>
                                <option value="2">Gestionnaire</option>
                                <option value="3">Invité</option>
                            </select>
                        </div>
                        <label class="col-sm-2 col-form-label text-right">Statut</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="e_status" id="e_status">
                                <option value="" id="v_status">---Changer---</option>
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="edit_user()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
@endsection
