<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!-- Listado -->
                    <template v-if="listado">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="tipo_comprobante">Tipo comprobante</option>
                                      <option value="num_comprobante">Número de comprobante</option>
                                      <option value="fecha_hora">Fecha-Hora</option> 
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive"> 
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Número Comprobante</th>
                                        <th>Fecha - Hora</th> 
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                        <td>
                                            <button type="button" @click="abrirModal('ingreso','actualizar',ingreso)" class="btn btn-success btn-sm">
                                              <i class="icon-eye"></i>
                                            </button>&nbsp;
                                            <template v-if="ingreso.estado == 'Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarIngreso(ingreso.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template> 
                                        </td>
                                        <td v-text="ingreso.usuario"></td>
                                        <td v-text="ingreso.nombre"></td>
                                        <td v-text="ingreso.tipo_comprobante"></td>
                                        <td v-text="ingreso.serie_comprobante"></td>
                                        <td v-text="ingreso.num_comprobante"></td> 
                                        <td v-text="ingreso.fecha_hora"></td>
                                        <td v-text="ingreso.total"></td>
                                        <td v-text="ingreso.impuesto"></td>
                                        <td v-text="ingreso.estado"></td> 
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <!-- FIN Listado -->
                    <!-- Detalle -->         
                    <template v-else>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <label for="">Proveedor(*)</label>
                                <v-select 
                                    :on-search="selectProveedor"
                                    label="nombre"
                                    :options="arrayProveedor"
                                    placeholder="Buscar Proveedores..."
                                    :onChange="getDatosProveedor">
                                    
                                </v-select>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="impuesto">
                            </div>
                        
                            <div class="col-md-4">
                                <label for="">Tipo Comprobante(*)</label>
                                <select class="form-control" v-model="tipo_comprobante">
                                    <option value="0">Seleccione</option> 
                                    <option value="BOLETA">Boleta</option> 
                                    <option value="FACTURA">Factura</option> 
                                    <option value="TICKET">Ticket</option> 
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="">Serie Comprobante</label>
                                <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">
                            </div>
                            <div class="col-md-4">
                                <label for="">Número Comprobante(*)</label>
                                <input type="text" class="form-control" v-model="num_comprobante" placeholder="123x">
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Articulo</label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="idarticulo" placeholder="Aquí ingrese el articulo">
                                        <button  class="btn btn-primary">...</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Precio</label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Cantidad</label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm"> 
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Articulo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>
                                                Articulo n
                                            </td>
                                            <td>
                                                <input type="number"  value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number"  value="2" class="form-control">
                                            </td>
                                            <td>
                                                $ 6.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>
                                                Articulo n
                                            </td>
                                            <td>
                                                <input type="number"  value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number"  value="2" class="form-control">
                                            </td>
                                            <td>
                                                $ 6.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>
                                                Articulo n
                                            </td>
                                            <td>
                                                <input type="number"  value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number"  value="2" class="form-control">
                                            </td>
                                            <td>
                                                $ 6.00
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial: </strong></td>
                                            <td>$ 5</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto: </strong></td>
                                            <td>$ 1</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto: </strong></td>
                                            <td>$ 1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row  pull-right">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-info">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- FIN Detalle -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
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
    import vSelect from 'vue-select';

    export default {
        data (){
            return {
                ingreso_id: 0,
                idproveedor : 0,
                nombre : '',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto : 0.18,
                total : 0.0, 
                arrayIngreso : [],
                arrayProveedor: [],
                arrayDetalle : [],
                listado: 1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIngreso : 0,
                errorMostrarMsjIngreso : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'num_comprobante',
                buscar : ''
            }
        },
        components:{
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            listarIngreso (page,buscar,criterio){
                let me=this;
                var url= '/ingreso?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayIngreso = respuesta.ingresos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectProveedor(search, loading ){
                let me=this;
                loading(true);
                var url= '/proveedor/selectProveedor?filtro='+search;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    q: search
                    me.arrayProveedor=respuesta.proveedores;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.idproveedor = val1.id;
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarIngreso(page,buscar,criterio);
            },
            registrarPersona(){
                if (this.validarPersona()){
                    return;
                }
                
                let me = this;

                axios.post('/user/registrar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'usuario': this.usuario,
                    'password': this.password,
                    'idrol' : this.idrol

                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarPersona(){
               if (this.validarPersona()){
                    return;
                }
                
                let me = this;

                axios.put('/user/actualizar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'usuario': this.usuario,
                    'password': this.password,
                    'idrol' : this.idrol,
                    'id': this.persona_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },            
            validarPersona(){
                this.errorPersona=0;
                this.errorMostrarMsjPersona =[];

                if (!this.nombre) this.errorMostrarMsjPersona.push("El nombre de la persona no puede estar vacío.");
                if (!this.usuario) this.errorMostrarMsjPersona.push("El nombre de usuario no puede estar vacío.");
                if (!this.password) this.errorMostrarMsjPersona.push("El password no puede estar vacío.");
                if (this.idrol==0) this.errorMostrarMsjPersona.push("Debes seleccionar un rol para el usuario.");

                if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

                return this.errorPersona;
            },
            mostrarDetalle(){
                this.listado = 0;
            },
            ocultarDetalle(){
                this.listado = 1;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.tipo_documento='DNI';
                this.num_documento='';
                this.direccion='';
                this.telefono='';
                this.email='';
                this.usuario='';
                this.password='';
                this.idrol=0;
                this.errorPersona=0;

            },
            abrirModal(modelo, accion, data = []){
                this.selectRol();
                switch(modelo){
                    case "persona":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Usuario';
                                this.nombre= '';
                                this.tipo_documento='DNI';
                                this.num_documento='';
                                this.direccion='';
                                this.telefono='';
                                this.email='';
                                this.usuario='';
                                this.password='';
                                this.idrol=0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Usuario';
                                this.tipoAccion=2;
                                this.persona_id=data['id'];
                                this.nombre = data['nombre'];
                                this.tipo_documento = data['tipo_documento'];
                                this.num_documento = data['num_documento'];
                                this.direccion = data['direccion'];
                                this.telefono = data['telefono'];
                                this.email = data['email'];
                                this.usuario = data['usuario'];
                                this.password = data['password'];
                                this.idrol = data['idrol'];
                                break;
                            }
                        }
                    }
                }
            },
            desactivarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                  title: 'Estás seguro?',
                  text: "Quieres desactivar el usuario!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, desactivar!',
                  cancelButtonText: 'No, cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                    let me = this;

                    axios.put('/user/desactivar',{ 
                        'id' : id
                    })
                    .then((response)=>{ 
                        
                        swalWithBootstrapButtons.fire(
                          'Desactivado!',
                          'Su archivo ha sido eliminado.',
                          'success'
                        )

                        me.listarPersona(1,'','nombre');

                    })
                    .catch((error)=>{
                        console.log(error);
                    })

                    
                  } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                    //swalWithBootstrapButtons.fire(
                    //  'Cancelled',
                    //  'Tu archivo imaginario es seguro :)',
                    //  'error'
                    //)
                  }
                })
            },
            activarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                  title: 'Estás seguro?',
                  text: "Quieres activar el usuario!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, activar!',
                  cancelButtonText: 'No, cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                    let me = this;

                    axios.put('/user/activar',{ 
                        'id' : id
                    })
                    .then((response)=>{ 
                        
                        swalWithBootstrapButtons.fire(
                          'Activado!',
                          'Su archivo ha sido activado.',
                          'success'
                        )

                        me.listarPersona(1,'','nombre');

                    })
                    .catch((error)=>{
                        console.log(error);
                    })

                    
                  } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                  ) {
                    //swalWithBootstrapButtons.fire(
                    //  'Cancelled',
                    //  'Tu archivo imaginario es seguro :)',
                    //  'error'
                    //)
                  }
                })
            },
        },
        mounted() {
            this.listarIngreso(1,this.buscar,this.criterio);
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
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>