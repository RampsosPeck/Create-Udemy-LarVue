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
                        <i class="fa fa-align-justify"></i> Ventas
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!-- Listado -->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="tipo_comprobante">Tipo comprobante</option>
                                      <option value="num_comprobante">Número de comprobante</option>
                                      <option value="fecha_hora">Fecha-Hora</option> 
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive"> 
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
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
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td>
                                            <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                              <i class="icon-eye"></i>
                                            </button>&nbsp;
                                            <template v-if="venta.estado == 'Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template> 
                                        </td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.tipo_comprobante"></td>
                                        <td v-text="venta.serie_comprobante"></td>
                                        <td v-text="venta.num_comprobante"></td> 
                                        <td v-text="venta.fecha_hora"></td>
                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.impuesto"></td>
                                        <td v-text="venta.estado"></td> 
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
                    <template v-else-if="listado==0">
                    <div class="card-body">
                        <div class="form-group row border"> 
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <v-select
                                        :on-search="selectCliente"
                                        label="nombre"
                                        :options="arrayCliente"
                                        placeholder="Buscar Clientes..."
                                        :onChange="getDatosCliente"                                        
                                    > 
                                    </v-select>
                                </div>
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
                            <div class="col-md-12 text-center">
                                <div v-show="errorVenta" class="row form-group">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Articulo <span style="color:red;" v-show="idarticulo==0">(*Seleccione)</span> </label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticuloVenta()" placeholder="Aquí ingrese el articulo">
                                        <button @click="abrirModal()"  class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Precio<span style="color:red;" v-show="precio==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Cantidad<span style="color:red;" v-show="cantidad==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Descuento </label>
                                    <input type="number" value="0" class="form-control" v-model="descuento">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
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
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button type="button" @click="eliminarDetalle(index)" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo"> 
                                            </td>
                                            <td>
                                                <input v-model="detalle.precio" type="number"  value="3" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{ detalle.stock }}</span> <input v-model="detalle.cantidad" type="number"  value="2" class="form-control">
                                            </td>
                                            <td><span style="color:red;" v-show="detalle.descuento>(detalle.precio*detalle.cantidad)">Descuento superior</span> 
                                                <input v-model="detalle.descuento" type="number"  value="2" class="form-control">
                                            </td>
                                            <td>
                                                {{ detalle.precio*detalle.cantidad-detalle.descuento }}
                                            </td>
                                        </tr>  
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Parcial: </strong></td>
                                            <td>$ {{ totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Impuesto: </strong></td>
                                            <td>$ {{ totalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Neto: </strong></td>
                                            <td>$ {{ total=calcularTotal }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6" > No hay articulos agregados commit</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row  pull-right">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-info">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border"> 
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor</label>
                                    <p v-text="proveedor"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <p v-text="impuesto"></p>
                            </div>
                        
                            <div class="col-md-4">
                                <label for="">Tipo Comprobante</label>
                                <p v-text="tipo_comprobante"></p>
                            </div>
                            <div class="col-md-4">
                                <label for="">Serie Comprobante</label>
                                <p v-text="serie_comprobante"></p>
                            </div>
                            <div class="col-md-4">
                                <label for="">Número Comprobante</label>
                                <p v-text="num_comprobante"></p>
                            </div> 
                        </div> 
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm"> 
                                    <thead>
                                        <tr> 
                                            <th>Articulo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo"> 
                                            </td>
                                            <td>
                                                <p v-text="detalle.precio"></p> 
                                            </td>
                                            <td>
                                                <p v-text="detalle.cantidad"></p>  
                                            </td>
                                            <td>
                                                {{ detalle.precio*detalle.cantidad }}
                                            </td>
                                        </tr>  
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Parcial: </strong></td>
                                            <td>$ {{ totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Impuesto: </strong></td>
                                            <td>$ {{ totalImpuesto=(total*impuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Neto: </strong></td>
                                            <td>$ {{ total  }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4" > No hay articulos agregados commit</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row  pull-right">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-info">Cerrar</button> 
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
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3"  v-model="criterioA" >
                                          <option value="nombre">Nombre</option>
                                          <option value="descripcion">Descripción</option>
                                          <option value="codigo">Código</option>
                                        </select>
                                        <input type="text" v-model="buscarA" @keyup.enter="listarArticuloVenta(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarArticuloVenta(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                           <div class="table-responsive">
                               <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Precio Venta</th>
                                            <th>Stock</th>   
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" @click="agregarDetalleModal(articulo)">
                                                  <i class="icon-check">Agregar</i>
                                                </button>  
                                            </td>
                                            <td v-text="articulo.codigo"></td>
                                            <td v-text="articulo.nombre"></td>
                                            <td v-text="articulo.nombre_categoria"></td>
                                            <td v-text="articulo.precio_venta"></td>
                                            <td v-text="articulo.stock"></td> 
                                            <td>
                                                <div v-if="articulo.condicion">
                                                        
                                                    <span class="badge badge-success">Activo</span>
                                                
                                                </div>
                                                <div v-else>
                                                        
                                                    <span class="badge badge-danger">Desactivado</span>
                                                
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                           </div>
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
                venta_id: 0,
                idcliente : 0,
                cliente : '', 
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto : 0.18,
                total : 0.0, 
                totalImpuesto : 0.0, 
                totalParcial : 0.0, 
                arrayVenta : [],
                arrayCliente: [],
                arrayDetalle : [],
                listado: 1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorVenta : 0,
                errorMostrarMsjVenta : [],
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
                buscar : '',
                criterioA : 'nombre',
                buscarA : '', 
                arrayArticulo: [],
                idarticulo: 0,
                codigo: '',
                articulo: '',
                precio: 0,
                cantidad: 0,
                descuento: 0, 
                stock: 0 
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

            },
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0; i<this.arrayDetalle.length; i++){
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad-this.arrayDetalle[i].descuento)
                }
                return resultado;
            }
        },
        methods : {
            listarVenta (page,buscar,criterio){
                let me=this;
                var url= '/venta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayVenta = respuesta.ventas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCliente(search,loading){
                let me=this;
                loading(true)

                var url= '/cliente/selectCliente?filtro='+search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayCliente=respuesta.clientes;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(respuesta);
                });
            },
            getDatosCliente(val1){
                let me = this;
                me.loading = true;
                me.idcliente = val1.id;
            },
            buscarArticuloVenta(){
                let me = this;
                var url = '/articulo/buscarArticuloVenta?filtro='+ me.codigo;

                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos;

                    if(me.arrayArticulo.length > 0){
                        me.articulo = me.arrayArticulo[0]['nombre'];
                        me.idarticulo = me.arrayArticulo[0]['id'];
                        me.precio = me.arrayArticulo[0]['precio_venta'];
                        me.stock = me.arrayArticulo[0]['stock'];
                    }else{
                        me.articulo = 'No existen articulos';
                        me.idarticulo = 0;
                    }
                }).catch(function (error){
                    console.log(error);
                });

            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarVenta(page,buscar,criterio);
            },
            encuentra(id){
                var sw = 0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idarticulo==id){
                        sw=true;
                    }
                }
                return sw;
            },
            agregarDetalle(){
                let me=this;

                if(me.idarticulo==0 || me.cantidad==0 || me.precio==0){

                }else{
                    if(me.encuentra(me.idarticulo)){
                        Swal.fire({
                          type: 'error',
                          title: 'Oops...',
                          text: 'Ese artículo ya fue agregado!' 
                        })
                    }else{
                        if(me.cantidad > me.stock){
                            Swal.fire({
                              type: 'error',
                              title: 'Oops...',
                              text: 'No hay stock disponible!' 
                            })
                        }else{
                            me.arrayDetalle.push({
                            idarticulo: me.idarticulo,
                            articulo: me.articulo,
                            cantidad: me.cantidad,
                            precio: me.precio, 
                            descuento: me.descuento, 
                            stock: me.stock, 
                            });

                            me.codigo="";
                            me.idarticulo=0;
                            me.articulo="";
                            me.cantidad=0;
                            me.precio=0;
                            me.descuento=0;
                            me.stock=0;
                        }
                    }
                }

            },
            agregarDetalleModal(data = []){
                let me = this;
                if(me.encuentra(data['id'])){
                        Swal.fire({
                          type: 'error',
                          title: 'Oops...',
                          text: 'Ese artículo ya fue agregado!' 
                        })
                    }else{
                        me.arrayDetalle.push({
                            idarticulo: data['id'],
                            articulo: data['nombre'],
                            cantidad: 1,
                            precio: data['precio_venta'],
                            descuento: 0,
                            stock:data['stock']
                        }); 

                        Swal.fire({
                          position: 'top-end',
                          type: 'success',
                          title: 'Articulo Agregado correctamente...',
                          showConfirmButton: false,
                          timer: 1500
                        })
                    }
            },
            listarArticuloVenta(buscar, criterio){
                let me = this;
                var url = '/articulo/listarArticuloVenta?buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url)
                .then((response)=>{
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos.data; 
                })
                .catch((error)=>{
                    console.log(error);
                })
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            registrarIngreso(){
                if (this.validarIngreso()){
                    return;
                }
                
                let me = this;

                axios.post('/ingreso/registrar',{
                    'idproveedor': this.idproveedor,
                    'tipo_comprobante': this.tipo_comprobante,
                    'serie_comprobante': this.serie_comprobante,
                    'num_comprobante' : this.num_comprobante,
                    'impuesto' : this.impuesto,
                    'total' : this.total,
                    'data' : this.arrayDetalle 

                }).then(function (response) { 
                    Swal.fire({
                          position: 'top-end',
                          type: 'success',
                          title: 'El ingreso se agregado correctamente...',
                          showConfirmButton: false,
                          timer: 1500
                        });
                    me.listado=1;
                    me.listarIngreso(1,'','num_comprobante');
                    me.idproveedor = 0;
                    me.tipo_comprobante = 'BOLETA'; 
                    me.serie_comprobante = '';
                    me.num_comprobante = '';
                    me.impuesto =0.18;  
                    me.total = 0.0;
                    me.idarticulo = 0;
                    me.articulo='';
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle=[];
                }).catch(function (error) {
                    console.log(error);
                });
            },           
            validarIngreso(){
                this.errorIngreso=0;
                this.errorMostrarMsjIngreso =[];

                if (this.idproveedor==0) this.errorMostrarMsjIngreso.push("Seleccione un Proveedor");
                if (this.tipo_comprobante==0) this.errorMostrarMsjIngreso.push("Seleccione el comprobante.");
                if (this.num_comprobante==0) this.errorMostrarMsjIngreso.push("Ingrese el número de comprobante.");
                if (this.impuesto==0) this.errorMostrarMsjIngreso.push("Ingrese el impuesto de compra.");
                if (this.arrayDetalle.length<=0) this.errorMostrarMsjIngreso.push("Ingrese detalles.");

                if (this.errorMostrarMsjIngreso.length) this.errorIngreso = 1;

                return this.errorIngreso;
            },
            mostrarDetalle()
            {
                 let me = this;
                this.listado = 0;
                me.idproveedor = 0;
                me.tipo_comprobante = 'BOLETA'; 
                me.serie_comprobante = '';
                me.num_comprobante = '';
                me.impuesto =0.18;  
                me.total = 0.0;
                me.idarticulo = 0;
                me.articulo='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
            },
            ocultarDetalle(){
                this.listado = 1;
            },
            verIngreso(id){
                let me=this;
                me.listado=2;

                //Obtener los datos del ingreso
                var arrayIngresoT=[];
                var url= '/ingreso/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayIngresoT = respuesta.ingreso;

                    me.proveedor = arrayIngresoT[0]['nombre'];
                    me.tipo_comprobante = arrayIngresoT[0]['tipo_comprobante'];
                    me.serie_comprobante = arrayIngresoT[0]['serie_comprobante'];
                    me.num_comprobante = arrayIngresoT[0]['num_comprobante'];
                    me.impuesto = arrayIngresoT[0]['impuesto'];
                    me.total = arrayIngresoT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
                var urld= '/ingreso/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles; 
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.modal=0;  
                this.tituloModal='';  
            },
            abrirModal(){
                this.arrayArticulo = [];
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios Articulos'; 
                        
            },
            desactivarIngreso(id){
                const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                  },
                  buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                  title: 'Estás seguro?',
                  text: "Quieres anular este ingreso?!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonText: 'Si, desactivar!',
                  cancelButtonText: 'No, cancelar!',
                  reverseButtons: true
                }).then((result) => {
                  if (result.value) {

                    let me = this;

                    axios.put('/ingreso/desactivar',{ 
                        'id' : id
                    }).then((response)=>{ 
                        
                        me.listarIngreso(1,'','num_comprobante');
                        swalWithBootstrapButtons.fire(
                          'Anulado!',
                          'El ingreso fue anulado con exito.',
                          'success'
                        )


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
            this.listarVenta(1,this.buscar,this.criterio);
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