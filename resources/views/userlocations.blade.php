@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header row"><div class="col-md-5">{{ __('Users Locations List') }}</div><div class="col-md-3"><button class="btn btn-success btn-block" data-toggle="modal" data-target="#modal">Ajouter une localisation</button></div></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


<table class="table table-bordered" id="UsersLocationsList" data-url="{{route('userdatatabletwo')}}" data-id="{{$data['user_id']}}">
  <thead>
    <tr>
      <th scope="col">S#</th>
      <th scope="col">Name</th>
      <th scope="col">Latitude</th>
      <th scope="col">Longitude</th>
      <th scope="col">Date</th>
      <th scope="col" width="11%">Actions</th>
    </tr>
  </thead>


</table>

                </div>
            </div>
        </div>
    </div>
</div>
<p style="display: none" class="auto resourcedata"
  data-url-two="{{url('getalllocations')}}"
  data-url-three="{{url('loadinitdatalocations')}}" data-token="{{ csrf_token() }}" data-url="{{url('postdata')}}" style="margin: 0px !important;">
  <div id="map"></div>
</p>
<!-- / .modal -->
<div id="modal" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header ">
                <div class="modal-title text-md">Veuillez entrer les informations de la localisation</div>
            </div>
            <div class="modal-body">
                <div class="col-md-12 text-center">
                    <small class="text-danger" id="error_field"></small>
                    <small class="text-success" id="success_field"></small>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="user_id" id="user_id" value="{{$data['user_id']}}">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Latitude</label>
                        <div class="col-sm-9">
                            <input type="number" id="lat" name="lat" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Longitude</label>
                        <div class="col-sm-9">
                            <input type="number" id="lng" name="lng" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Catégorie</label>
                        <div class="col-sm-9">
                            <select class="form-control categorie" name="categorie" id="categorie">
                                @foreach ($categories as $categorie)
                                    <option value="{{$categorie->id}}">{{$categorie->location_type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="save_location()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-edit" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header ">
                <div class="modal-title text-md">Veuillez modifier la catégorie de la localisation à mettre à jour</div>
            </div>
            <div class="modal-body">
                <div class="col-md-12 text-center">
                    <small class="text-danger" id="e_error_field"></small>
                    <small class="text-success" id="e_success_field"></small>
                </div>
                <form action="" method="POST">
                    <input type="hidden" id="e_id" name="e_id">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Latitude</label>
                        <div class="col-sm-9">
                            <input type="number" id="e_lat" name="e_lat" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Longitude</label>
                        <div class="col-sm-9">
                            <input type="number" id="e_lng" name="e_lng" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Catégorie</label>
                        <div class="col-sm-9">
                            <select class="form-control e_categorie" name="e_categorie" id="e_categorie">
                                <option value="" id="v_categorie">---Changer---</option>
                                @foreach ($categories as $categorie)
                                    <option value="{{$categorie->id}}">{{$categorie->location_type}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="edit_location()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
@endsection
