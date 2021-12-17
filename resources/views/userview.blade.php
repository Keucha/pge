<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
      html, body, #map {
        height: 100%;
        margin: 0px;
        padding: 0px
      }

      #panel {
        position: absolute;
        top: 5px;
        left: 50%;
        margin-left: -180px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }

      #bar {
        width: auto;
        background-color: rgba(255, 255, 255, 0.75);
        margin: 8px;
        padding: 4px;
        border-radius: 4px;
      }

      #autoc {
        width: 100%;
        box-sizing: border-box;
      }
      .accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc;
}

.accordion:after {
  content: '\002B';
  color: #777;
  font-weight: bold;
  float: right;
  margin-left: 5px;
}

.active:after {
  content: "\2212";
}

.panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}
.main-content {
    position: absolute;
    z-index: 1;
    background: white;
    opacity: 0.8;
    margin-top: 52px;
    margin-left: 12px;
    width: 165px;
    font-size: 16px;
    font-family: 'Roboto';
    font-weight: bold;

    text-decoration: none;
}
.main-content a {
    font-size: 16px;
    font-family: system-ui;
    font-weight: bold;
    color:#538ff4;
    text-decoration: none;
    padding-left: 5px;
}

.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: #f1f1f1;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}
.loc-addr {
    font-size: 11px;
    padding-left: 3px;
}
.loc-heading {
    font-size: 15px;
    padding-left: 3px;
    color:black;
    font-weight: 900 !important;
}

.location-container {
    border-radius: 3px;
    background: white;
    margin: 5px;
    padding-bottom: 3px;
    padding-top: 3px;
    cursor: pointer;

}

.category-container{
   border-radius: 3px;
    background: white;
    margin: 5px;
    padding-bottom: 3px;
    padding-top: 3px;
    cursor: pointer;
}

.locations-list-dev{
  min-height: 885px !important;
    max-height: atuo !important;
    height: auto !important;
}
@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}

    </style>

    <script src="https://www.gstatic.com/external_hosted/jquery2.min.js"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?libraries=drawing,places,geometry&key=AIzaSyB3ooLNpuPYxeG-NX9j1t-b0XeaHQBHvVs"></script>
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="{{asset('js/custommap.js')}}"></script>
  </head>

  <body>

    <div class="main-content">
      <div class="toggle-location">

        <a href="javascript:;" class="btn-location-show" onclick="openNav()">Show Locations</a>

      </div>
      <div class="toggle-categories">
        <a href="javascript:;" class="btn-location-show" onclick="openNavOne()">Show Categories</a>
      </div>
     <!--  <hr style="margin: 3px;
    border-bottom: black;
    margin-left: 0px;
    margin-right: 0px;" /> -->
      <div class="locations-list">

<div id="mySidenavOne" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNavOne()">&times;</a>
<h3 class="heading-one" style="position: absolute;
    color: white;
    margin-top: -45px;
    margin-left: 11px;
    font-size: 29px;">Categories</h3>
    <div class="categories-list-dev" style="height: 788px;"></div>
</div>

<div id="mySidenav" class="sidenav" style="overflow-x: scroll !important;">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <h3 class="heading-one" style="position: absolute;
    color: white;
    margin-top: -45px;
    margin-left: 11px;
    font-size: 29px;">Locations</h3>
    <div class="locations-list-dev"></div>

</div>

<!-- <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; open</span> -->



      </div>
    </div>
    <div id="map"></div>
    <div id="map-rounded"></div>

    <div id="bar">
  <p class="auto resourcedata"
  data-url-two="{{url('getalllocations')}}"
  data-url-three="{{url('loadinitdatalocations')}}" data-token="{{ csrf_token() }}" data-url="{{url('postdata')}}" style="margin: 0px !important;">

<div class="container-one" style="margin: 0px !important;">
      <div class="username-txt" style="font-size: 17px;
    text-transform: capitalize;
    font-weight: bold;color: #538ff4;">{{auth::user()->name}}</div>

 <a class="dropdown-item" style="float:right;font-size: 12px;color: black;font-weight: bold;font-family: 'Roboto';" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>


<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>


      </p>

    </div>
      <!-- <p><a id="clear" href="#">Click here</a> to clear map.</p> -->
    </div>
  </body>
</html>
