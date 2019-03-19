<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" class="btn btn-secondary" @click="abrirModal('categoria','registrar')" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" id="opcion" name="opcion">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" id="texto" name="texto" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria in arrayCategoria" :key="categoria.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('categoria','actualizar',categoria)">
                                          <i class="icon-pencil">Actualizar</i>
                                        </button> &nbsp;
                                         <template v-if="categoria.condicion">
                                         	<button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.id)"><i class="icon-trash">Desactivar</i></button>
                                         </template>
                                         <template v-else>
                                         	<button type="button" class="btn btn-info btn-sm" @click="activarCategoria(categoria.id)"><i class="icon-ok">Activar</i></button>
                                         </template>
                                    </td>
                                    <td v-text="categoria.nombre"></td>
                                    <td v-text="categoria.descripcion"></td>
                                    <td>
                                    	<div v-if="categoria.condicion">
                                    	    	
                                        	<span class="badge badge-success">Activo</span>
                                    	
                                    	</div>
                                    	<div v-else>
                                    	    	
                                        	<span class="badge badge-danger">Desactivado</span>
                                    	
                                    	</div>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#">Ant</a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">4</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->

            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" >
                <div class="modal-dialog modal-dialog-centered modal-primary " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" 
                            @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                           				<span>(*) Ingrese el nombre de la categoria.</span>             
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <textarea type="text" v-model="descripcion" class="form-control" placeholder="Descripción de la categoria"></textarea>
                                    </div>
                                </div>
                                <div v-show="errorCategoria" class="row form-group">
                                	<div class="text-center text-error">
                                		<div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error">
                                			
                                		</div>
                                	</div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="cerrarModal()" class="btn btn-warning">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCategoria()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2"  class="btn btn-info" @click="actualizarCategoria()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal--> 
        </main>
</template>

<script>
    export default {
    	data(){
    		return {
    			categoria_id : 0,
    			nombre: '',
    			descripcion: '',
    			arrayCategoria : [],
    			modal : 0,
    			tituloModal : '',
    			tipoAccion : 0,
    			errorCategoria : 0,
    			errorMostrarMsjCategoria : []
    		}
    	},
    	methods: {
    		listarCategoria(){
    			let me = this;
    			axios.get('/categorias')
    			.then((response)=>{
    				me.arrayCategoria = response.data;
    			})
    			.catch((error)=>{
    				console.log(error);
    			})
    		},
    		registrarCategoria(){
    			if(this.validarCategoria()){
    				return;
    			}

    			let me = this;

    			axios.post('/categorias/registrar',{
    				'nombre' : this.nombre,
    				'descripcion' : this.descripcion
    			})
    			.then((response)=>{
    				me.cerrarModal();
    				me.listarCategoria();
    			})
    			.catch((error)=>{
    				console.log(error);
    			})
    		},
    		actualizarCategoria(){
    			if(this.validarCategoria()){
    				return;
    			}

    			let me = this;

    			axios.put('/categorias/actualizar',{
    				'nombre' : this.nombre,
    				'descripcion' : this.descripcion,
    				'id' : this.categoria_id
    			})
    			.then((response)=>{
    				me.cerrarModal();
    				me.listarCategoria();
    			})
    			.catch((error)=>{
    				console.log(error);
    			})
    		},
    		desactivarCategoria(){
    			const swalWithBootstrapButtons = Swal.mixin({
				  customClass: {
				    confirmButton: 'btn btn-success',
				    cancelButton: 'btn btn-danger'
				  },
				  buttonsStyling: false,
				})

				swalWithBootstrapButtons.fire({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this!",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Yes, delete it!',
				  cancelButtonText: 'No, cancel!',
				  reverseButtons: true
				}).then((result) => {
				  if (result.value) {
				    swalWithBootstrapButtons.fire(
				      'Deleted!',
				      'Your file has been deleted.',
				      'success'
				    )
				  } else if (
				    // Read more about handling dismissals
				    result.dismiss === Swal.DismissReason.cancel
				  ) {
				    swalWithBootstrapButtons.fire(
				      'Cancelled',
				      'Your imaginary file is safe :)',
				      'error'
				    )
				  }
				})
    		},
    		validarCategoria(){
    			this.errorCategoria = 0;
    			this.errorMostrarMsjCategoria = [];

    			if(!this.nombre) 
    				this.errorMostrarMsjCategoria.push("El nombre de la categoria no puede estar vacio");
    			if(this.errorMostrarMsjCategoria.length)
    				this.errorCategoria = 1;

    			return  this.errorCategoria;

    		},
    		abrirModal(modelo, accion, data = []){
    			switch(modelo){
    				case "categoria":
    				{
    					switch(accion){
    						case 'registrar':
    						{
    							this.modal = 1;
    							this.tituloModal = 'Registrar Categoria';
    							this.nombre = '';
    							this.descripcion = '';
    							this.tipoAccion = 1;
    							break;
    		 				};
    						case 'actualizar':
    						{
    							//console.log(data);
    							this.modal = 1;
    							this.tituloModal = 'Actualizar Categoria';
    							this.tipoAccion = 2;
    							this.categoria_id = data['id'];
    							this.nombre = data['nombre'];
    							this.descripcion = data['descripcion'];
    							break;
    						}
    					}
    				}
    			}
    		},
    		cerrarModal(){
    			this.modal =0;
    			this.tituloModal='';
    			this.nombre='';
    			this.descripcion='';
    		}
    	},
        mounted() {
            this.listarCategoria();
        }
    }
</script>

<style>
	.modal-content{
		width: 100% !important;
		position: absolute !important;
	}
	.mostrar{
		display: list-item !important;
		opacity: 1 !important;
		position: absolute !important;
		background-color: #3c29297a !important;
	}
	.div-error{
		display: flex;
		justify-content: center;
	}
	.text-error{
		color: red !important;
		font-weight: bold;
	}
</style>












