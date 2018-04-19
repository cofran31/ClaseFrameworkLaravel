@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Cliente: {{ $personas->nombre}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
		</div>
	</div>

			{!!Form::model($personas,['method'=>'PATCH','route'=>['cliente.update',$personas->idpersona]])!!}﻿
            {{Form::token()}}
          <div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
					<div class="form-group">
		            	<label for="nombre">Nombre</label>
		            	<input type="text" name="nombre" required value="{{$personas->nombre}}" class="form-control" placeholder="Nombre...">
	            	</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
					<div class="form-group">
		            	<label for="direccion">Direccion</label>
		            	<input type="text" name="direccion"  value="{{$personas->direccion}}" class="form-control" placeholder="Direccion...">
	            	</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
					<div class="form-group">
			            	<label for="tipo_documento">Documento</label>
			            	<select name="tipo_documento" class="form-control">
			            		@if($personas->tipo_documento == 'DNI')
			            			<option value="DNI" selected>DNI</option>
			            			<option value="RUC">RUC</option>
			            			<option value="PAS">PAS</option>
			            			<option value="IFE">IFE</option>
			            		@elseif($personas->tipo_documento == 'RUC')
			            			<option value="DNI" >DNI</option>
			            			<option value="RUC" selected>RUC</option>
			            			<option value="PAS">PAS</option>
			            			<option value="IFE">IFE</option>
			            		@elseif($personas->tipo_documento == 'PAS')
			            			<option value="DNI" >DNI</option>
			            			<option value="RUC" >RUC</option>
			            			<option value="PAS" selected>PAS</option>
			            			<option value="IFE">IFE</option>
			            		@else 
			            			<option value="DNI" >DNI</option>
			            			<option value="RUC" >RUC</option>
			            			<option value="PAS" >PAS</option>
			            			<option value="IFE" selected>IFE</option>
			            		@endif
			            	</select>
	            	</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
					<div class="form-group">
		            	<label for="num_documento">Num Documento</label>
		            	<input type="text" name="num_documento" required value="{{$personas->num_documento}}" class="form-control" >
	            	</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
					<div class="form-group">
		            	<label for="telefono">Telefono</label>
		            	<input type="text" value="{{$personas->telefono}}" name="telefono" class="form-control" >
	            	</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">	
					<div class="form-group">
		            	<label for="email">Email</label>
		            	<input type="text" name="email" value="{{$personas->email}}"  class="form-control" placeholder="Email...">
	            	</div>
				</div>


				 <div class="form-group">
            		<center><button class="btn btn-primary" type="submit">Guardar</button>
            		<button class="btn btn-danger" type="reset">Cancelar</button></center>
            	</div>
				
			</div>
			{!!Form::close()!!}		
@endsection