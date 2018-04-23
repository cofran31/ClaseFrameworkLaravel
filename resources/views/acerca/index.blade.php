@extends ('layouts.admin')
@section ('contenido')

@if(count($errors)>0)
<div class="alert alert-danger">
    <ul>
        @foreach($error->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>
</div>
@endif
<table border="0" style="width:70%;" align="center">
    <tr><td colspan="2" style="text-align: center">
           <img src="{{URL::asset('img/andina.png')}}" alt="{{'Universidad Andina'}}"  class=""/> 
    </td></tr>
    <tr><td style="width:25%">
            <h2 style="color:darkred"><b>Acerca de:</b></h2>
        </td><td style="width:75%"> 
            <h3>{{$titulo}}</h3>
        </td></tr>
        <tr><td>
            <h2 style="color:darkred"><b> Curso:</b></h2>
        </td><td> 
            <h3> {{$curso}}</h3>
        </td></tr>
    <tr><td>
            <h2 style="color:darkred"><b> Docente:</b></h2>
        </td><td> 
            <h3> {{$docente}}</h3>
        </td></tr>

    <tr><td>
            <h2 style="color:darkred"><b> Maestrante:</b></h2>
        </td><td> 
            <h3> {{$alumno}}</h3>
        </td></tr>
</table>

@endsection