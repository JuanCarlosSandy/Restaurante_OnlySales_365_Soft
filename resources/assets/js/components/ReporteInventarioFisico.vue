<template>
    <main class="main">
        <ol class="breadcrumb">
             <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Inventario Fisico Valorado
                    <button type="button" @click="abrirModal()"
                    class="btn btn-primary">
                    <i class="fa fa-search"></i>&nbsp;Filtros</button>
                    <button @click="exportarPDF" class="btn btn-danger">Exportar a PDF</button>
                    <button @click="exportarExcel" class="btn btn-success">Exportar a EXCEL</button>

                    <div class="col-md-3">
                        <div class="d-flex flex-column">
                            <label class="mb-1"> MODO VISTA </label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="tipoSeleccionado" value="item" @change="cambiarTipo">
                                    <label class="form-check-label ms-2">Por Item</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" v-model="tipoSeleccionado" value="lote" @change="cambiarTipo">
                                    <label class="form-check-label ms-2">Por Lote</label>
                                </div>
                        </div>
                     </div>
                </div>
                <div class="card-body">
                    <div v-if="tipoSeleccionado == 'item'" class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <!-- <th>Opciones</th> -->
                                    <th>Almacen</th>
                                    <th>Linea</th>
                                    <th>Producto</th>
                                    <th>Unidad X Paq.</th>                                   
                                    <th>Saldo_stock_total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="inventario in arrayReporte" :key="inventario.id">
                                    <!-- <td>
                                        <button type="button" @click="abrirModal('almacenes','actualizar',inventario)" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                    </td> -->
                                    <td v-text="inventario.nombre_almacen"></td>
                                    <td v-text="inventario.nombre_categoria"></td>
                                    <td v-text="inventario.nombre_producto"></td>
                                    <td v-text="inventario.unidad_paquete"></td>
                                    <td v-text="inventario.saldo_stock_total"></td>

                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <!--#####################################-LIStADO LOTE-###############-->
                    <div v-if="tipoSeleccionado == 'lote'" class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Almacen</th>
                                    <th>Fecha Ingreso</th>
                                    <th>Linea</th>
                                    <th>Producto</th>
                                    <th>Unid.X.Paq</th>
                                    <th>Costo Unidad</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Saldo Stock</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="inventario in arrayReporte" :key="inventario.id">
                                    <td v-text="inventario.nombre_almacen"></td>
                                    <td v-text="inventario.fecha_ingreso"></td>
                                    <td v-text="inventario.nombre_categoria"></td>
                                    <td v-text="inventario.nombre_producto"></td>
                                    <td v-text="inventario.unidad_paquete"></td>
                                    <td v-text="inventario.precio_costo_unid"></td>
                                    <td v-text="inventario.fecha_vencimiento"></td>
                                    <td v-text="inventario.saldo_stock"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--######################################-hasta AQUI#################-->
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
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>

        <!--Inicio del modal agregar/actualizar-->
        <div class="modal" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Filtros de reportes</h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <div class="form-group row">

                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Almacen <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Seleccione un almacen" disabled
                                            v-model="almacenseleccionada.nombre_almacen" :class="{ 'is-invalid': errores.idAlmacen }" 
                                            @input="validarCampo('codigo')">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                @click="abrirModal2('Almacen')">...</button>
                                        </div>
                                    </div>
                                    <p class="text-danger" v-if="errores.idcategoria">{{ errores.idcategoria }}</p>
                                             
                        
                                </div>

                                <div class="col-md-6">

                                    
                                    
                                    <label for="" class="font-weight-bold">Linea <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Seleccione una linea" disabled
                                            v-model="lineaseleccionada.nombre"
                                            :class="{ 'is-invalid': errores.idcategoria }" @input="validarCampo('codigo')">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                @click="abrirModal2('Lineas')">...</button>
                                        </div>
                                    </div>
                                    <p class="text-danger" v-if="errores.idcategoria">{{ errores.idcategoria }}</p>
                                    
                                
                                </div> 
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Fecha Inicio: <span class="text-danger">*</span> </label>
                                    <input class="form-control" type="date" v-model="fechaInicio" >
                                    
                                    
                                </div>
                                <div class="col-md-6">
                                    
                                    <label for="" class="font-weight-bold">Fecha Fin: <span class="text-danger">*</span></label>
                                    <input class="form-control" type="date" v-model="fechaFin" >
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
                            <button type="submit" @click="listaReporte(); cerrarModal()" class="btn btn-primary">Visualizar Reporte</button>
                        </div>

                </div>
                <!-- /.modal-content -->
            </div>


            <!-- /.modal-dialog -->
        </div>

        <div class="modal " tabindex="-1" :class="{ 'mostrar': modal2 }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal2"></h4>
                        <button type="button" class="close" @click="modal2 = false" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                        <option v-if="tituloModal2 !== 'Grupos'" value="nombre">Nombre</option>
                                        <option v-else-if="tituloModal2 == 'Grupos'" value="nombre_grupo">Grupo</option>
                                        <!-- <option v-if="tituloModal2=='Grupos'" value="nombre_grupo">Nombre_grupo</option> -->
                                    </select>
                                  
                                    <input v-if="tituloModal2 == 'Lineas'" type="text" v-model="buscarA"
                                        @keyup="listarLinea(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">                              
                                    <input v-if="tituloModal2 == 'Almacen'" type="text" v-model="buscarA"
                                        @keyup="listarAlmacen(1, buscarA, criterioA)" class="form-control"
                                        placeholder="Texto a buscar">

                                   
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" v-if="tituloModal2 !== 'Almacen'">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>

                                        <th>Nombre</th>
                                        <!-- <th>Estado</th> -->
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="arrayelemento in arrayBuscador" :key="arrayelemento.id">
                                        <td>
                                            <button type="button" @click="seleccionar(arrayelemento)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="arrayelemento.id"></td>

                                        <td v-text="arrayelemento.nombre"></td>
                                        
                                        <td v-if="tituloModal2 == 'Lineas' ">
                                            <div v-if="arrayelemento.condicion">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                            <!--###########################################################-->
                            
                            <!--##################################################################3-->
                            <table class="table table-bordered table-striped table-sm" v-else-if="tituloModal2 == 'Almacen'">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Id</th>
                                        <th>Nombre Sucursal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="arrayelemento in arrayBuscador" :key="arrayelemento.id">
                                        <td>
                                            <button type="button" @click="seleccionar(arrayelemento)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                           
                                        </td>
                                        <td v-text="arrayelemento.id"></td>
                                        <td v-text="arrayelemento.nombre_almacen"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--##################################################################3-->
                        </div>
                        <nav v-if="tituloModal2 == 'Lineas'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaLinea(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaLinea(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaLinea(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                       
                        <nav v-else-if="tituloModal2 == 'Almacen'">
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaAlmacen(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActivedMar ? 'active' : '']">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaAlmacen(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPaginaAlmacen(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="modal2 = false">Cerrar</button>
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
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx-js-style';

    export default {
        data (){
            return {
                datosFormulario: {
                    nombre: '',
                    nombre_almacen: '',
                    descripcion: '',
                    unidad_paquete: 0,
                    precio_costo_unid: 0,
                    precio_costo_paq: 0,
                    precio_venta: 0,
                    precio_uno: 0,
                    precio_dos: 0,
                    precio_tres: 0,
                    precio_cuatro: 0,
                    stock: 0,
                    codigo: '',
                    codigo_alfanumerico: '',
                    descripcion_fabrica: '',
                    idcategoria: null,
                },
                arrayInventario: [],
                //---------------------
                //arrayAlmacenes: [],
                //AlmacenSeleccionado: 1,
                //idalmacen: 0,
                tipoSeleccionado: 'item',
                
                //---------------------
                // almacen_id:0,
                // nombre_almacen: '',
                // ubicacion: '',
                // encargado: '',
                // telefono: '',
                // lugar: '',
                // observacion: '',

                // tipoAccion : 0,
                // errorMostrarMsjIndustria: [],
                // errorIndustria : 0,
                pagination: this.createPaginationObject(),
                paginationMedida: this.createPaginationObject(),
                offset: {
                    pagination: 3,
                    paginationMedida: 3,
                },
                criterio : 'nombre',
                buscar : '',

                //FILTROS
                modal : 0,

                tituloModal2: '',
                modal2: false,
                errores: {},
                nombre: '',
                descripcion: '',
                criterioA: 'nombre',
                buscarA: '',

                almacenseleccionada:[],
                lineaseleccionada: [],

                arrayBuscador: [],
                arrayReporte:[],


                //fechas
                fechaInicio:'',
                fechaFin:'',



                //Validacion de inputs
                lineaseleccionadaVacio: false,
                marcaseleccionadaVacio: false,
                industriaseleccionadaVacio: false,
                almacenseleccionadaVacio: false,
                

            }
        },
        computed:{
            isActived: function () {
            return this.pagination.current_page;
            },
            isActivedM: function () {
                return this.pagination.current_page;
            },
            isActivedMar: function () {
                return this.pagination.current_page;
            },

            pagesNumber: function () {
                return this.calculatePages(this.pagination, this.offset.pagination);
            },
            pagesNumberMedida: function () {
                return this.calculatePages(this.paginationMedida, this.offset.paginationMedida);
        },

        },
        methods : {
            calculatePages: function (paginationObject, offset) {
            if (!paginationObject.to) {
                return [];
            }

            var from = paginationObject.current_page - offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (offset * 2);
            if (to >= paginationObject.last_page) {
                to = paginationObject.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },

        createPaginationObject() {
            return {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            }
        },

        async validarCampo(campo) {
            this.asignarCampos();
            try {
                await esquemaArticulos.validateAt(campo, this.datosFormulario);
                this.errores[campo] = null;
            } catch (error) {
                this.errores[campo] = error.message;
            }
        },

        calcularPrecioValorMoneda(precio) {
            console.log(precio)
            return ((precio * parseFloat(this.monedaPrincipal)).toFixed(2))
        },
        convertDolar(precio) {
            return (precio / parseFloat(this.monedaPrincipal))
        },

        asignarCampos() {
            this.datosFormulario.idcategoria = this.lineaseleccionada.id;
            this.datosFormulario.idAlmacen = this.almacenseleccionada.id;
        },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarInventario(page,buscar,criterio);
            },
            //---------------------------------------
            listarInventario (){
                let me=this;
                let url = '/inventarios/itemLote/' + me.tipoSeleccionado + '?&idAlmacen=' + me.almacenSeleccionado + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    console.log("ARRAy:",respuesta);
                    me.arrayInventario = respuesta.inventarios;
                    console.log('LLEGA:',me.arrayInventario);
                    //me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectAlmacen() {
                let me = this;
                var url = '/almacen/selectAlmacen';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayAlmacenes = respuesta.almacenes;
                    console.log('ALMACEN:',me.arrayAlmacenes);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDatosAlmacen() {
                let me = this;
                if (me.AlmacenSeleccionado !== '') {
                    me.loading = true;
                    me.almacenSeleccionado = me.AlmacenSeleccionado; // Almacenar el valor seleccionado
                    me.idalmacen = Number(me.AlmacenSeleccionado);
                    console.log('IDalmacen: ' + me.idalmacen);
                    me.listarInventario();
                }
            },
            cambiarTipo() {
                this.listaReporte(); // Actualizar datos de almacén
                //this.listarInventario(); // Listar inventario basado en almacén seleccionado
            },
            
            abrirModal() {
                this.almacenseleccionada = false;
                this.lineaseleccionada = false;
                this.fechaInicio = '';
                this.fechaFin ='';
                this.modal = 1;
            },
        
            cerrarModal() {
            this.modal = 0;
            this.lineaseleccionadaVacio = false;
            this.almacenseleccionadaVacio = false;
        },
        
        abrirModal2(titulo) {
             if (titulo == "Lineas") {
                this.listarLinea(1, '', 'nombreLinea');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.lineaseleccionadaVacio = false;

            }else if (titulo == "Almacen") {
                this.listarAlmacen(1, '', 'nombre_almacen');
                this.modal2 = true;
                this.tituloModal2 = titulo;
                this.almacenseleccionadaVacio = false;

            }
        },

        seleccionar(selected) {
            if (this.tituloModal2 == "Lineas") {
                    this.lineaseleccionada = selected;

            }else if (this.tituloModal2 == "Almacen") {
                    this.almacenseleccionada = selected;
                
            }
            
            console.log("Seleccionado", selected);
            this.arrayBuscador = [];
            this.modal2 = false;
        },
        
        listaReporte() {
            let me = this;
            var url = '/reporte-inventario-fisico-valorado/'+me.tipoSeleccionado +'?&fecha_vencimiento=2026-01-01 ';

            // Agregar los parámetros obligatorios
            url += '&idAlmacen=' + this.almacenseleccionada.id + '&idLinea=' + this.lineaseleccionada.id ;

            // Agregar las fechas de inicio y fin
            //url += '&fechaInicio=' + me.fechaInicio + '&fechaFin=' + me.fechaFin;

            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.total_saldofisico = respuesta.total_saldo;
                    me.arrayReporte = respuesta.inventarios_valorado;
                    console.log("array reporte",me.arrayReporte);
                })
                .catch(function (error) {
                    console.log('ERRORES', error);
                });
        },




        listarLinea(page, buscar, criterio) {
            let me = this;
            var url = '/categoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayBuscador = respuesta.categorias.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarAlmacen(page, buscar, criterio) {
            let me = this;
                var url = '/almacen/selectAlmacen';
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayBuscador = respuesta.almacenes;
                    console.log('ALMACEN:',me.arrayAlmacenes);
                    console.log('Buscador:',me.arrayBuscador);

                })
                .catch(function (error) {
                    console.log(error);
                });
        },




        cambiarPaginaLinea(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            me.listarLinea(page, buscar, criterio);
            //Envia la petición para visualizar la data de esa página
        },


        cambiarPaginaAlmacen(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarAlmacen(page, buscar, criterio);
        },

        exportarPDF() {
            if (this.tipoSeleccionado === 'item') {
                this.exportarPDFITEM();
            } else if (this.tipoSeleccionado === 'lote') {
                this.exportarPDFLOTE();
            }
        },

        exportarExcel() {
            if (this.tipoSeleccionado === 'item') {
                this.exportarExcelITEM();
            } else if (this.tipoSeleccionado === 'lote') {
                this.exportarExcelLOTE();
            }
        },

        exportarPDFITEM() {
            const pdf = new jsPDF('landscape');
            
            const titulo = 'INVENTARIO FISICO VALORADO';
            const fechaInicio = `Fecha Inicio: ${this.fechaInicio}`;
            const almacen = `Almacen: ${this.almacenseleccionada.nombre_almacen}`;
            const linea = `Linea: ${this.lineaseleccionada.nombre}`;


            pdf.setFont('helvetica');
            pdf.setFontSize(16); // Tamaño de letra más grande para el título
            pdf.text(titulo, 100, 10);

            pdf.setFontSize(10); // Tamaño de letra más pequeño para los elementos restantes
            pdf.text(fechaInicio, 15, 20);
            pdf.text(almacen, 125, 20);
            pdf.text(linea, 125, 30);

            const tableYPosition = 40;

            const columns = ['Almacen', 'Linea','Producto','Unidad x Paquete','Stock'];
            const rows = this.arrayReporte.map(item => [
                    item.nombre_almacen,
                    item.nombre_categoria,
                    item.nombre_producto,
                    item.unidad_paquete,
                    item.saldo_stock_total,
                ]);


            pdf.autoTable({ head: [columns], body: rows, startY: tableYPosition });

            pdf.save('resumen_movimientos_fisicos.pdf');
        },

        exportarPDFLOTE() {
            const pdf = new jsPDF('landscape');
            
            const titulo = 'INVENTARIO FISICO VALORADO';
            const fechaInicio = `Fecha Inicio: ${this.fechaInicio}`;
            const almacen = `Almacen: ${this.almacenseleccionada.nombre_almacen}`;
            const linea = `Linea: ${this.lineaseleccionada.nombre}`;


            pdf.setFont('helvetica');
            pdf.setFontSize(16); // Tamaño de letra más grande para el título
            pdf.text(titulo, 100, 10);

            pdf.setFontSize(10); // Tamaño de letra más pequeño para los elementos restantes
            pdf.text(fechaInicio, 15, 20);
            pdf.text(almacen, 125, 20);
            pdf.text(linea, 125, 30);

            const tableYPosition = 40;

            const columns = ['Almacen', 'Fecha Ingreso', 'Linea','Producto','Unidad x Paquete','Costo x Unidad', 'Fecha de Vencimiento', 'Stock'];
            const rows = this.arrayReporte.map(item => [
                    item.nombre_almacen,
                    item.fecha_ingreso,
                    item.nombre_categoria,
                    item.nombre_producto,
                    item.unidad_paquete,
                    item.precio_costo_unid,
                    item.fecha_vencimiento,
                    item.saldo_stock,
                ]);

            pdf.autoTable({ head: [columns], body: rows, startY: tableYPosition });

            pdf.save('resumen_movimientos_fisicos.pdf');
        },

        exportarExcelITEM() {
            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.aoa_to_sheet([]);
            const startRow = 5;
            
            // Merge de celdas para el título
            worksheet['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: 6 } }];
            // Título del reporte
            worksheet['A1'] = { t: 's', v: 'INVENTARIO FISICO VALORADO', s: { 
                font: { sz: 16, bold: true, color: { rgb: 'FFFFFF' } },
                alignment: { horizontal: 'center', vertical: 'center' },
                fill: { fgColor: { rgb: '3669a8' } } } };

            // Estilo para la fecha
            const fechaStyle = { font: { bold: true, color: { rgb: '000000' } } };
            // Fechas de inicio y fin
            worksheet['A2'] = { t: 's', v: `Fecha inicio: ${this.fechaInicio}`, s: fechaStyle };
            worksheet['F2'] = { t: 's', v: `Almacen: ${this.almacenseleccionada.nombre_almacen}`, s: fechaStyle };
            worksheet['F3'] = { t: 's', v: `Linea: ${this.lineaseleccionada.nombre}`, s: fechaStyle };


            // Estilo para los encabezados
            const headerStyle = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '3669a8' } } };
            // Cabeceras de las columnas
            const headers = ['Almacen', 'Linea' ,'Producto','Unidad x Paquete','Stock'];

            // Añadir las cabeceras a la hoja de cálculo
            headers.forEach((header, index) => {
                worksheet[XLSX.utils.encode_cell({ r: 3, c: index })] = { t: 's', v: header, s: headerStyle };
            });

            // Añadir los datos al kardex
            Object.values(this.arrayReporte).forEach((item, rowIndex) => {
                const rowData = [
                item.nombre_almacen,
                    item.nombre_categoria,
                    item.nombre_producto,
                    item.unidad_paquete,
                    item.saldo_stock_total,
                ];

                // Añadir la fila al kardex
                XLSX.utils.sheet_add_aoa(worksheet, [rowData], { origin: `A${startRow + rowIndex}` });
            });


            // Establecer el ancho de las columnas
            const columnWidths = [
                { wch: 29.33 },
                { wch: 26.33 },
                { wch: 29.56 },
                { wch: 21.11 },
                { wch: 38.78 },
                { wch: 19.67 },
                { wch: 10.22 },
            ];
            worksheet['!cols'] = columnWidths;

            // Añadir la hoja de cálculo al libro
            XLSX.utils.book_append_sheet(workbook, worksheet, 'HOJA 1');

            // Descargar el archivo
            XLSX.writeFile(workbook, 'inventario_fisico_valorado.xlsx');
        },

        exportarExcelLOTE() {
            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.aoa_to_sheet([]);
            const startRow = 5;
            
            // Merge de celdas para el título
            worksheet['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: 9 } }];
            // Título del reporte
            worksheet['A1'] = { t: 's', v: 'INVENTARIO FISICO VALORADO', s: { 
                font: { sz: 16, bold: true, color: { rgb: 'FFFFFF' } },
                alignment: { horizontal: 'center', vertical: 'center' },
                fill: { fgColor: { rgb: '3669a8' } } } };

            // Estilo para la fecha
            const fechaStyle = { font: { bold: true, color: { rgb: '000000' } } };
            // Fechas de inicio y fin
            worksheet['A2'] = { t: 's', v: `Fecha inicio: ${this.fechaInicio}`, s: fechaStyle };
            worksheet['F2'] = { t: 's', v: `Almacen: ${this.almacenseleccionada.nombre_almacen}`, s: fechaStyle };
            worksheet['F3'] = { t: 's', v: `Linea: ${this.lineaseleccionada.nombre}`, s: fechaStyle };


            // Estilo para los encabezados
            const headerStyle = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '3669a8' } } };
            // Cabeceras de las columnas
            const headers = ['Almacen', 'Fecha Ingreso', 'Linea','Producto','Unidad x Paquete','Costo x Unidad', 'Fecha de Vencimiento', 'Stock'];

            // Añadir las cabeceras a la hoja de cálculo
            headers.forEach((header, index) => {
                worksheet[XLSX.utils.encode_cell({ r: 3, c: index })] = { t: 's', v: header, s: headerStyle };
            });

            // Añadir los datos al kardex
            Object.values(this.arrayReporte).forEach((item, rowIndex) => {
                const rowData = [
                    item.nombre_almacen,
                    item.fecha_ingreso,
                    item.nombre_categoria,
                    item.nombre_producto,
                    item.unidad_paquete,
                    item.precio_costo_unid,
                    item.fecha_vencimiento,
                    item.saldo_stock,
                ];

                // Añadir la fila al kardex
                XLSX.utils.sheet_add_aoa(worksheet, [rowData], { origin: `A${startRow + rowIndex}` });
            });


            // Establecer el ancho de las columnas
            const columnWidths = [
                { wch: 25.44 },
                { wch: 14.67 },
                { wch: 21.56 },
                { wch: 37.56 },
                { wch: 16.78 },
                { wch: 13.22 },
                { wch: 23.33 },
                { wch: 8.78 },
            ];
            worksheet['!cols'] = columnWidths;

            // Añadir la hoja de cálculo al libro
            XLSX.utils.book_append_sheet(workbook, worksheet, 'HOJA 1');

            // Descargar el archivo
            XLSX.writeFile(workbook, 'inventario_fisico_valorado.xlsx');
        },


        },
        mounted() {
            //this.listarInventario(1,this.buscar,this.criterio);
            this.getDatosAlmacen();
            //this.listarInventario(1,this.buscar,this.criterio);
            this.listarInventario();
            this.selectAlmacen();


        },
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