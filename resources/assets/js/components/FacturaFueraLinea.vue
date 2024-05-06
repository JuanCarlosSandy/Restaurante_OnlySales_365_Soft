<template>
    <main class="main">
        <!-- Breadcrumb -->
        
            <!-- Ejemplo de tabla Listado -->
        <Panel header="Ventas Fuera de Línea" >

            <template #icons>
                <Button label="Nueva Venta" icon="pi pi-plus" class="p-button-sm p-button-raised p-button-success" @click="mostrarDetalle" :style="buttonStyle"/>
            </template>

                
                <!-- Listado-->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Número Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup="listarVenta(1, buscar, criterio)"
                                        class="form-control" placeholder="Texto a buscar">
                                    <!--button type="submit" @click="listarVenta(1, buscar, criterio)" class="btn btn-primary"><i
                                            class="fa fa-search"></i> Buscar</button-->
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
                                        <th>Número Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(venta) in arrayVenta" :key="venta.id">
                                    <td>
                                    <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                        <i class="icon-eye"></i>
                                    </button> &nbsp;

                                    <template v-if="venta.estado == 'Registrado'">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                        <i class="icon-trash"></i>
                                        </button> &nbsp;
                                    </template>

                                    <button type="button" @click="imprimirTicket(venta.id)" class="btn btn-info btn-sm" >
                                        Imprimir Ticket
                                    </button>

                                    </td>
                                    <td v-text="venta.usuario"></td>
                                    <td v-text="venta.cliente"></td>
                                    <td v-text="venta.tipo_comprobante"></td>
                                    <td v-text="venta.num_comprobante"></td>
                                    <td v-text="venta.fecha_hora"></td>
                                    <td v-text="venta.total"></td>
                                    <td v-text="venta.estado"></td>
                                </tr>
                                </tbody>

                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)"
                                        v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!--Fin Listado-->
                <!-- Detalle-->
                <template v-else-if="listado == 0">

                    <div>
                        <DataView :value="arrayMenu" layout="grid" :paginator="true" :rows="23">
                            <template #grid="slotProps">
                                <div class="product-container" style="padding-right: 6px; padding-left: 6px; padding-bottom: 10px;">
                                <Button class="p-button-text product-button" type="button" @click="agregarDetalleModal(slotProps.data)">
                                    <Card class="project-card" @click="console.log('Producto seleccionado:')">
                                        <template #header>
                                            <div class="image-container">
                                                <img :src="'/img/menu/' + slotProps.data.fotografia" alt="Product Image" class="product-image">
                                            </div>
                                        </template>

                                        <template #title>
                                            <div class="product-name">{{ truncateAndCapitalize(slotProps.data.nombre) }}</div>
                                        </template>
                                        
                                        <template #footer>
                                            <div class="footer-content">
                                                <div class="price">Bs {{ slotProps.data.precio_venta }}</div>
                                                <Button icon="pi pi-pencil" class="p-button-sm p-button-warning rounded-bottom-right" @click.stop="visibleRight = true" />
                                            </div>
                                        </template>
                                    </Card>
                                </Button>
                                </div>
                            </template>

                            <template #empty>Menu vacio</template>
                        </DataView>
                    </div>


<div class="floating-buttons">
    <Button class="p-button-lg p-button-success floating-button" @click.stop="visibleFull = true">
        <i class="pi pi-shopping-cart" style="font-size: 3rem" ></i>
        <Badge :value="arrayDetalle.length" size="large" severity="danger"></Badge>
    </Button>
</div>

<Sidebar :visible.sync="visibleFull" :baseZIndex="1000" position="full">
    <template #header>
        <div class="sidebar-full">
            <i class="pi pi-shopping-cart" style="font-size: 2rem" ></i>
            <h4>Carrito</h4>
        </div>
    </template>

    <div class="col-md-4 " style="max-width: none ;margin: 0 auto;">
                            <div class="form-group row border">
                                <div class="col-md-4">
                                    <div v-show="paraLlevar" class="form-group">
                                        <label for="">Cliente(*)</label>
                                        <input type="text" id="cliente" class="form-control" placeholder="Nombre del Cliente" v-model="cliente" ref="cliente">
                                    </div>
                                    <div v-show="!paraLlevar" class="form-group">
                                        <label for="">Mesero(*)</label>
                                        <input type="text" id="mesero" class="form-control" placeholder="Nombre del Mesero"
                                        v-model="usuario_autenticado" ref="mesero" readonly>
                                    </div>
                                </div>
                                <input type="hidden" id="nombreCliente" class="form-control" readonly value="Sin Nombre">
                                <input type="hidden" id="idcliente" class="form-control" readonly value="7">
                                <input type="hidden" id="tipo_documento" class="form-control" readonly value="1">
                                <input type="hidden" id="complemento_id" class="form-control" v-model="complemento_id" ref="complementoIdRef" readonly>
                                <input type="hidden" id="usuarioAutenticado" class="form-control" v-model="usuarioAutenticado" readonly>
                                <input type="hidden" id="documento" class="form-control" readonly value="0000">
                                <input type="hidden" id="email" class="form-control" readonly value="sinnombre@gmail.com">
                                <input type="hidden" id="idAlmacen" class="form-control" readonly value="1">
                                <div  v-show="!paraLlevar" class="col-md-5">
                                    <div class="form-group">
                                        <label>Num Mesa(*)</label>
                                        <input type="number" id="mesa" class="form-control" v-model="mesa">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Número Ticket</label>
                                        <input type="text" id="num_comprobante" class="form-control" v-model="num_comprob" ref="numeroComprobanteRef" readonly>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Tipo Comprobante(*)</label>
                                        <select class="form-control" v-model="tipo_comprobante" ref="tipoComprobanteRef">
                                            <option value="0">Seleccione</option>
                                            <option value="TICKET">Ticket</option>
                                            <option value="FACTURA">Factura</option>
                                            <option value="BOLETA">Boleta</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">Para llevar:
                                            <span class="text-danger">*</span>

                                        </label>
                                    </div>
                                    <div><InputSwitch v-model="paraLlevar" style="transform: scale(0.75);"/></div>
                                </div>
                                

                                <div class="col-md-3">
                                    <div class="form-group" v-if="scodigorecepcion === 5 || scodigorecepcion === 6 || scodigorecepcion === 7">
                                        <label>Código CAFC</label>
                                        <input type="text" id="cafc" class="form-control" v-model="cafc" ref="cafc">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div v-show="errorVenta" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Artículo</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>

                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detalle.articulo">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-show="detalle.cantidad > detalle.stock">Stock: {{ detalle.stock }}</span>
                                                    <input v-model="detalle.cantidad" type="number" class="form-control">
                                                </td>
                                                <td>
                                                    {{ (detalle.precio * detalle.cantidad - detalle.descuento).toFixed(2) }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Sub Total: Bs.</strong></td>
                                                <td id="subTotal">{{ totalParcial=(calcularSubTotal).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Descuento Adicional: Bs.</strong></td>
                                                <input id="descuentoAdicional" v-model="descuentoAdicional" type="number"
                                                    class="form-control">
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Total Neto: Bs.</strong></td>
                                                <td id="montoTotal">{{ total=(calcularTotal).toFixed(2) }}</td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
                                                    No hay articulos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registrar()">Registrar Venta</button>
                                </div>
                            </div>
                        </div>
</Sidebar>

<Sidebar :visible.sync="visibleRight" :baseZIndex="1000" position="right">
    <h3>Right Sidebar</h3>
</Sidebar>

                </template>

                <!--<template v-else-if="listado == 3">
                    <div class="col-md-4 " style="max-width: none ;margin: 0 auto;">
                            <div class="form-group row border">
                                <div class="col-md-4">
                                    <div v-show="paraLlevar" class="form-group">
                                        <label for="">Cliente(*)</label>
                                        <input type="text" id="cliente" class="form-control" placeholder="Nombre del Cliente" v-model="cliente" ref="cliente">
                                    </div>
                                    <div v-show="!paraLlevar" class="form-group">
                                        <label for="">Mesero(*)</label>
                                        <input type="text" id="mesero" class="form-control" placeholder="Nombre del Mesero"
                                        v-model="usuario_autenticado" ref="mesero" readonly>
                                    </div>
                                </div>
                                <input type="hidden" id="nombreCliente" class="form-control" readonly value="Sin Nombre">
                                <input type="hidden" id="idcliente" class="form-control" readonly value="7">
                                <input type="hidden" id="tipo_documento" class="form-control" readonly value="1">
                                <input type="hidden" id="complemento_id" class="form-control" v-model="complemento_id" ref="complementoIdRef" readonly>
                                <input type="hidden" id="usuarioAutenticado" class="form-control" v-model="usuarioAutenticado" readonly>
                                <input type="hidden" id="documento" class="form-control" readonly value="0000">
                                <input type="hidden" id="email" class="form-control" readonly value="sinnombre@gmail.com">
                                <input type="hidden" id="idAlmacen" class="form-control" readonly value="1">
                                <div  v-show="!paraLlevar" class="col-md-5">
                                    <div class="form-group">
                                        <label>Num Mesa(*)</label>
                                        <input type="number" id="mesa" class="form-control" v-model="mesa">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Número Ticket</label>
                                        <input type="text" id="num_comprobante" class="form-control" v-model="num_comprob" ref="numeroComprobanteRef" readonly>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Tipo Comprobante(*)</label>
                                        <select class="form-control" v-model="tipo_comprobante" ref="tipoComprobanteRef">
                                            <option value="0">Seleccione</option>
                                            <option value="TICKET">Ticket</option>
                                            <option value="FACTURA">Factura</option>
                                            <option value="BOLETA">Boleta</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">Para llevar:
                                            <span class="text-danger">*</span>

                                        </label>
                                    </div>
                                    <div><InputSwitch v-model="paraLlevar" style="transform: scale(0.75);"/></div>
                                </div>
                                

                                <div class="col-md-3">
                                    <div class="form-group" v-if="scodigorecepcion === 5 || scodigorecepcion === 6 || scodigorecepcion === 7">
                                        <label>Código CAFC</label>
                                        <input type="text" id="cafc" class="form-control" v-model="cafc" ref="cafc">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div v-show="errorVenta" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Artículo</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>

                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detalle.articulo">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-show="detalle.cantidad > detalle.stock">Stock: {{ detalle.stock }}</span>
                                                    <input v-model="detalle.cantidad" type="number" class="form-control">
                                                </td>
                                                <td>
                                                    {{ (detalle.precio * detalle.cantidad - detalle.descuento).toFixed(2) }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Sub Total: Bs.</strong></td>
                                                <td id="subTotal">{{ totalParcial=(calcularSubTotal).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Descuento Adicional: Bs.</strong></td>
                                                <input id="descuentoAdicional" v-model="descuentoAdicional" type="number"
                                                    class="form-control">
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="3" align="right"><strong>Total Neto: Bs.</strong></td>
                                                <td id="montoTotal">{{ total=(calcularTotal).toFixed(2) }}</td>
                                            </tr>
                                        </tbody>

                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
                                                    No hay articulos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registrar()">Registrar Venta</button>
                                </div>
                            </div>
                        </div>
                </template>-->


                <!-- Fin Detalle-->
                <!--Ver ingreso-->
                <template v-else-if="listado == 2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="impuesto"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante</label>
                                    <p v-text="num_comprobante"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo">
                                            </td>
                                            <td v-text="detalle.medida">
                                            </td>
                                            <td v-text="detalle.precio">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td v-text="detalle.descuento">
                                            </td>
                                            <td>
                                                {{ detalle.precio * detalle.cantidad - detalle.descuento }}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{ totalParcial=(total - totalImpuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{ totalImpuesto=(total * impuesto).toFixed(2) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{ total }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay articulos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin ver ingreso-->
            <!-- Fin ejemplo de tabla Listado -->
        </Panel>
    </main>
</template>

<script>

import InputSwitch from 'primevue/inputswitch';
import vSelect from 'vue-select';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions';
import Badge from 'primevue/badge';
import Dialog from 'primevue/dialog';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Panel from 'primevue/panel';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Sidebar from 'primevue/sidebar';
import Card from 'primevue/card';

export default {
    data() {
        return {

            // primeVue variables
            paraLlevar: false,
            categoria_busqueda: '',
            arrayCategoriasMenu: [],
            arrayCategoriasProducto: [],
            arrayMenu: [],
            layout: 'grid',
            activeIndex: 0,
            visibleFull: false,
            visibleRight: false,

            buttonStyle: {
                width: '400px',
            },

            ejemploCarrito: 9,

            // -----------------------

            venta_id: 0,
            idcliente: 0,
            usuarioAutenticado: null,
            puntoVentaAutenticado: null,
            cliente: '',
            email: '',
            mesa: 0,
            cliente: '',
            nombreCliente: '',
            documento: '',
            tipo_documento: '',
            complemento_id: '',
            descuentoAdicional: 0.00,
            tipo_comprobante: 'TICKET',
            serie_comprobante: '',
            last_comprobante: 0,
            num_comprob: "",
            impuesto: 0.18,
            total: 0.0,
            totalImpuesto: 0.0,
            totalParcial: 0.0,
            arrayVenta: [],
            arrayCliente: [],
            arrayDetalle: [],
            arrayProductos: [],
            mostrarTipoComprobante: false,
            listado: 1,
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorVenta: 0,
            errorMostrarMsjVenta: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: '',
            buscar: '',
            criterioA: '0',
            buscarA: '',
            arrayArticulo: [],
            idarticulo: 0,
            codigo: '',
            articulo: '',
            medida: '',
            codigoProductoSin: '',
            precio: 0,
            cantidad: 1,
            descuento: 0,
            sTotal: 0,
            stock: 0,
            valorMaximoDescuento: '',
            mostrarDireccion: true,
            mostrarCUFD: true,
            mostrarEnviarPaquete: true,
            mostrarValidarPaquete: false,
            cafc: '',
            scodigomotivo: null,
            usuario_autenticado: '',
            //almacenes
            arrayAlmacenes: [],
            idAlmacen: 1,
        }
    },
    components: {
        vSelect,
        Button,
        Dropdown,
        DataView,
        DataViewLayoutOptions,
        Badge,
        Dialog,
        InputSwitch,
        TabView,
        TabPanel,
        Panel,
        InputText,
        InputNumber,
        Sidebar,
        Card
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },

        //Calcula los elementos de la paginación
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },

        calcularTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado += (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento)
            }
            resultado -= this.descuentoAdicional;
            return resultado;
        },

        calcularSubTotal: function () {
            var resultado = 0.0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                resultado = resultado + (this.arrayDetalle[i].precio * this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento)
            }
            return resultado;
        },
    
    },
    methods: {

    verDetalle(producto) {
        console.log('PULSADO');
        console.log('Producto pulsado:', producto);
    },

    truncateAndCapitalize(text) {
        const maxLength = 14;
        text = text.length > maxLength ? text.substring(0, maxLength - 3) + '...' : text;
        return text.replace(/\b\w/g, (char) => char.toUpperCase());
    },

    editarMenu(event) {
        event.stopPropagation();
        console.log('EDITANDO MENU');
    },

        updateButtonStyle() {
            const windowWidth = window.innerWidth;
            //console.log(windowWidth);

            if (windowWidth <= 576) {
                this.buttonStyle.width = '145px';
            } else {
                this.buttonStyle.width = '400px';
            }
        },

        abrirCarrito() {
            console.log('abriendo carrito');
            this.abrirVentanaVenta();
            this.goToNextTab();
        },


        abrirVentanaVenta() {
            let me = this;

            me.listado = 3;
        },

        getCategoriasMenu() {
            let me = this;

            var url = '/categorias_menu/getAll';
            axios.get(url).then(function (response) {

                let respuesta = response.data;
                me.arrayCategoriasMenu = respuesta.categorias_menu;
                console.log('menu categorias:', me.arrayCategoriasMenu);
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        getCategoriasProductos() {
            let me = this;
            var url = '/categoria/selectCategoria';
            axios.get(url).then(function (response) {

                let respuesta = response.data;
                me.arrayCategoriasProducto = respuesta.categorias;
                console.log('productos categorias:', me.arrayCategoriasProducto);
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        atajoButton: function (event) {
            //console.log(event.keyCode);
            //console.log(event.ctrlKey);
            if (event.shiftKey && event.keyCode === 81) {
                event.preventDefault();
                this.$refs.impuestoRef.focus();
            }
            if (event.shiftKey && event.keyCode === 87) {
                event.preventDefault();
                this.$refs.serieComprobanteRef.focus();
            }
            if (event.shiftKey && event.keyCode === 69) {
                event.preventDefault();
                this.$refs.numeroComprobanteRef.focus();
            }
            if (event.shiftKey && event.keyCode === 82) {
                event.preventDefault();
                this.$refs.articuloRef.focus();
            }
            if (event.shiftKey && event.keyCode === 84) {
                event.preventDefault();
                this.$refs.precioRef.focus();
            }
            if (event.shiftKey && event.keyCode === 89) {
                event.preventDefault();
                this.$refs.cantidadRef.focus();
            }
            if (event.shiftKey && event.keyCode === 85) {
                event.preventDefault();
                this.$refs.descuentoRef.focus();
            }
        },

        nextNumber() {
            if (!this.num_comprob || this.num_comprob === "") {
                this.last_comprobante++;
                // Completa con ceros a la izquierda hasta alcanzar 5 dígitos
                this.num_comprob = this.last_comprobante.toString().padStart(5, "0");
            }
        },


        listarVenta(page, buscar, criterio) {
            let me = this;
            var url = '/venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayVenta = respuesta.ventas.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        selectCliente(search, loading) {
            let me = this;
            loading(true)
            var url = '/cliente/selectCliente?filtro=' + search;
            axios.get(url).then(function (response) {
                //console.log(response.clientes);
                let respuesta = response.data;
                q: search
                me.arrayCliente = respuesta.clientes;
                loading(false)
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getDatosCliente(val1) {
            let me = this;
            me.loading = true;
            me.idcliente = val1.id;
            //console.log(val1);
            this.email = val1.email;
            this.nombreCliente = val1.nombre;
            this.documento = val1.num_documento;
            this.tipo_documento = val1.tipo_documento;
            this.complemento_id = val1.complemento_id;

        },

        buscarArticulo() {
            let me = this;
            var url = '/articulo/buscarArticuloVenta?filtro=' + me.codigo;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);
                me.arrayArticulo = respuesta.articulos;

                if (me.arrayArticulo.length > 0) {
                    me.articulo = me.arrayArticulo[0]['nombre'];
                    me.idarticulo = me.arrayArticulo[0]['id'];
                    me.codigo = me.arrayArticulo[0]['codigo'];
                    me.precio = me.arrayArticulo[0]['precio_venta'];
                    me.stock = me.arrayArticulo[0]['stock'];
                    me.medida = me.arrayArticulo[0]['medida'];
                    me.codigoProductoSin = me.arrayArticulo[0]['codigoProductoSin'];
                }
                else {
                    me.articulo = 'No existe este articulo';
                    me.idarticulo = 0;
                }
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        imprimirTicket(id) {
            axios.get('/venta/imprimir/'+id, { responseType: 'blob' })
                .then(function(response) {
                window.location.href = "docs/ticket.pdf";
                const fileURL = 'docs/ticket.pdf';
                window.open(fileURL, '_blank');
                console.log("Se generó el Ticket correctamente");
                })
                .catch(function(error) {
                console.log(error);
                });
            },

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarVenta(page, buscar, criterio);
        },
        cambiarPaginaA(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarArticulo(page, buscar, criterio);
        },
        encuentra(id) {
            var sw = 0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                if (this.arrayDetalle[i].idarticulo == id) {
                    sw = true;
                }
            }
            return sw;
        },
        eliminarDetalle(index) {
            let me = this;
            me.arrayDetalle.splice(index, 1);
        },
        agregarDetalle() {
            let me = this;

            let actividadEconomica = 474110;
            let codigoProductoSin = document.getElementById("codigoProductoSin").value;
            let codigoProducto = document.getElementById("codigo").value;
            let descripcion = document.getElementById("nombre_producto").value;
            let cantidad = document.getElementById("cantidad").value;
            let unidadMedida = 57;
            let precioUnitario = document.getElementById("precio").value;
            let montoDescuento = document.getElementById("descuento").value;
            let subTotalValor = document.getElementById("sTotal");
            let subTotal = subTotalValor.textContent;
            let numeroSerie = null;
            let numeroImei = null;


            if (me.idarticulo == 0 || me.cantidad == 0 || me.precio == 0) {

            } else {
                if (me.encuentra(me.idarticulo)) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este Artículo ya se encuentra agregado!',
                    })
                } else {
                    if (me.cantidad > me.stock) {
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'No hay stock disponible!',
                        })
                    } else {
                        me.arrayDetalle.push({
                            idarticulo: me.idarticulo,
                            articulo: me.articulo,
                            medida: me.medida,
                            cantidad: me.cantidad,
                            precio: me.precio,
                            descuento: me.descuento,
                            stock: me.stock,
                        });

                        me.arrayProductos.push({
                            actividadEconomica: actividadEconomica,
                            codigoProductoSin: codigoProductoSin,
                            codigoProducto: codigoProducto,
                            descripcion: descripcion,
                            cantidad: cantidad,
                            unidadMedida: unidadMedida,
                            precioUnitario: precioUnitario,
                            montoDescuento: montoDescuento,
                            subTotal: subTotal,
                            numeroSerie: numeroSerie,
                            numeroImei: numeroImei
                        });

                        me.codigo = '';
                        me.idarticulo = 0;
                        me.articulo = '';
                        me.medida = '';
                        me.cantidad = 0;
                        me.precio = 0;
                        me.descuento = 0;
                        me.stock = 0;
                        me.sTotal = 0;
                    }
                }

            }

        },
        agregarDetalleModal(data = []) {
            let me = this;
            if (me.encuentra(data['id'])) {
                swal({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este Artículo ya se encuentra agregado!',
                })
            } else {
                me.arrayDetalle.push({
                    idarticulo: data['id'],
                    articulo: data['nombre'],
                    cantidad: 1,
                    precio: data['precio_venta'],
                    descuento: 0,
                    stock: data['stock'],
                    medida: data['medida'],
                });
            }
        },
        listarArticulo(page, criterioA) {
            let me = this;
            var url = '/articulo/listarArticuloVenta?page=' + page + '&criterio='+ criterioA + '&idAlmacen='+ this.idAlmacen;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);
                me.arrayArticulo = respuesta.articulos.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarMenu(buscar, criterio) {
            let me = this;
            var url = '/menu/getAllMenu?buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayMenu = respuesta.articulos;
                me.pagination = respuesta.pagination;
                console.log('lista menu: ', me.arrayMenu);
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        obtenerDatosUsuario() {
            axios.get('/venta')
                .then(response => {
                    this.usuarioAutenticado = response.data.usuario.usuario;
                    this.usuario_autenticado = this.usuarioAutenticado;
                    this.puntoVentaAutenticado = response.data.usuario.idpuntoventa;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        registrar() {
            this.registrarVenta();
            //this.paqueteFactura();
            //this.num_comprob;
        },
        registrarVenta() {
            if (this.validarVenta()) {
                return;
            }

            let me = this;

            axios.post('/venta/registrar', {
                'idcliente': this.idcliente,
                'cliente': this.cliente,
                'tipo_comprobante': this.tipo_comprobante,
                'serie_comprobante': this.serie_comprobante,
                'num_comprobante': this.num_comprob,
                'impuesto': this.impuesto,
                'total': this.total,
                'idAlmacen': this.idAlmacen,
                'data': this.arrayDetalle

            }).then(function (response) {
                //console.log(response.data.id);
                if (response.data.id > 0) {
                    me.listado = 1;
                    me.listarVenta(1, '', 'num_comprob');
                    me.idproveedor = 0;
                    me.cliente = '';
                    me.tipo_comprobante = 'TICKET';
                    me.serie_comprobante = '';
                    me.num_comprob = '';
                    me.impuesto = 0.18;
                    me.total = 0.0;
                    me.idarticulo = 0;
                    me.articulo = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.stock = 0;
                    me.codigo = '';
                    me.descuento = 0;
                    me.arrayDetalle = [];
                    //window.open('/factura/imprimir/' + response.data.id);
                    swal(
                            'VENTA REGISTRADA',
                            'Correctamente',
                            'success'
                        )
                        me.arrayProductos = [];
                        me.listarVenta(1, '', 'id');
                } else {
                    if (response.data.valorMaximo) {
                        //alert('El valor de descuento no puede exceder el '+ response.data.valorMaximo);
                        swal(
                            'Aviso',
                            'El valor de descuento no puede exceder el ' + response.data.valorMaximo,
                            'warning'
                        )
                        return;
                    } else {
                        //alert(response.data.caja_validado); 
                        swal(
                            'Aviso',
                            response.data.caja_validado,
                            'warning'
                        )
                        return;
                    }
                    //console.log(response.data.valorMaximo)
                }

            }).catch(function (error) {
                console.log(error);
            });
        },

        selectAlmacen() {
            let me = this;
            var url = '/almacen/selectAlmacen';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayAlmacenes = respuesta.almacenes;
                console.log(me.arrayAlmacenes);

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getAlmacenProductos(almacen) {
            let me = this;
            me.idAlmacen = 1;
            console.log(me.idAlmacen);
        },
        validarVenta() {
            let me = this;
            me.errorVenta = 0;
            me.errorMostrarMsjVenta = [];
            var art;

            me.arrayDetalle.map(function (x) {
                if (x.cantidad > x.stock) {
                    art = x.articulo + " Stock insuficiente";
                    me.errorMostrarMsjVenta.push(art);
                }
            });

            if (!me.cliente) me.errorMostrarMsjVenta.push("Ingrese el Nombre de un Cliente");
            if (me.tipo_comprobante == 0) me.errorMostrarMsjVenta.push("Seleccione el Comprobante");
            if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra");
            if (me.arrayDetalle.length <= 0) me.errorMostrarMsjVenta.push("Ingrese detalles");

            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

            return me.errorVenta;
        },

        paqueteFactura() {
            let me = this;

            let numeroFactura = document.getElementById("num_comprobante").value;
            let cuf = "464646464";
            let cufdValor = document.getElementById("cufdValor");
            let cufd = cufdValor.textContent;
            let direccionValor = document.getElementById("direccion");
            let direccion = direccionValor.textContent;
            var tzoffset = (new Date()).getTimezoneOffset() * 60000;
            let fechaEmision = (new Date(Date.now() - tzoffset)).toISOString().slice(0, -1);
            let id_cliente = document.getElementById("idcliente").value;
            let nombreRazonSocial = document.getElementById("nombreCliente").value;
            let tipoDocumentoIdentidad = 1;
            let numeroDocumento = document.getElementById("documento").value;
            let complemento = document.getElementById("complemento_id").value;
            let montoTotalValor = document.getElementById("montoTotal");
            let montoTotal = montoTotalValor.textContent;
            let descuentoAdicional = document.getElementById("descuentoAdicional").value;
            let leyenda = "Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.";
            let usuario = document.getElementById("usuarioAutenticado").value;
            let codigoPuntoVenta = this.puntoVentaAutenticado;

            let cafc = this.scodigorecepcion === 5 || this.scodigorecepcion === 6 || this.scodigorecepcion === 7
            ? document.getElementById("cafc").value
            : null;



            var factura = [];
            factura.push({
                cabecera: {
                    nitEmisor: "5153610012",
                    razonSocialEmisor: "365 SOFT",
                    municipio: "Cochabamba",
                    telefono: "77490451",
                    numeroFactura: numeroFactura,
                    cuf: cuf,
                    cufd: cufd,
                    codigoSucursal: 0,
                    direccion: direccion,
                    codigoPuntoVenta: codigoPuntoVenta,
                    fechaEmision: fechaEmision,
                    nombreRazonSocial: nombreRazonSocial,
                    codigoTipoDocumentoIdentidad: tipoDocumentoIdentidad,
                    numeroDocumento: numeroDocumento,
                    complemento: complemento,
                    codigoCliente: numeroDocumento,
                    codigoMetodoPago: 1,
                    numeroTarjeta: null,
                    montoTotal: montoTotal,
                    montoTotalSujetoIva: montoTotal,
                    codigoMoneda: 1,
                    tipoCambio: 1,
                    montoTotalMoneda: montoTotal,
                    montoGiftCard: null,
                    descuentoAdicional: descuentoAdicional,
                    codigoExcepcion: 0,
                    cafc: cafc,
                    leyenda: leyenda,
                    usuario: usuario,
                    codigoDocumentoSector: 1
                }
            })
            me.arrayProductos.forEach(function (prod) {
                factura.push({ detalle: prod })
            })

            var datos = { factura };

            axios.post('/venta/paqueteFactura', {
            factura: datos,
            id_cliente: id_cliente,
            fechaEmision: fechaEmision,
            cufd: cufd,
            cafc: cafc,
        })
        .then(function (response) {
            var data = response.data;
            me.listarVenta(1, '', 'id');
            if (data.message === "Factura registrada correctamente") {
                swal(
                    'FACTURA REGISTRADA',
                    'Correctamente',
                    'success'
                )
                me.arrayProductos = [];
            }
        })
        .catch(function (error) {
            swal({
                title: 'ERROR AL REGISTRAR LA FACTURA',
                type: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            })
                });
        },

        mostrarDetalle() {
            let me = this;
            me.selectAlmacen();
            me.listado = 0;
            
            me.nombreCliente = '';
            me.idcliente = 0;
            me.tipo_documento = '';
            me.complemento_id = '';
            me.documento = '';
            me.email = '';
            me.cafc = '';
            me.idproveedor = 0;
            me.tipo_comprobante = 'TICKET';
            me.serie_comprobante = '';
            me.nextNumber();
            //me.num_comprobante = '';
            me.impuesto = 0.18;
            me.total = 0.0;
            me.idarticulo = 0;
            me.articulo = '';
            me.cantidad = 0;
            me.precio = 0;
            me.arrayDetalle = [];
        },

        ocultarDetalle() {
            this.listado = 1;
        },

        verVenta(id) {
            let me = this;
            me.listado = 2;

            //Obtener datos del ingreso
            var arrayVentaT = [];
            var url = '/venta/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayVentaT = respuesta.venta;

                me.cliente = arrayVentaT[0]['nombre'];
                me.tipo_comprobante = arrayVentaT[0]['tipo_comprobante'];
                me.serie_comprobante = arrayVentaT[0]['serie_comprobante'];
                me.num_comprobante = arrayVentaT[0]['num_comprobante'];
                me.impuesto = arrayVentaT[0]['impuesto'];
                me.total = arrayVentaT[0]['total'];
            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/venta/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayDetalle = respuesta.detalles;

            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
            me.idproveedor = 0;
                    me.cliente = '';
                    me.tipo_comprobante = 'TICKET';
                    me.serie_comprobante = '';
                    me.num_comprob = '';
                    me.impuesto = 0.18;
                    me.total = 0.0;
                    me.idarticulo = 0;
                    me.articulo = '';
                    me.cantidad = 0;
                    me.precio = 0;
                    me.stock = 0;
                    me.codigo = '';
                    me.descuento = 0;
                    me.arrayDetalle = [];
        },
        abrirModal() {
            if (this.idAlmacen == 0) {
                return;
            }
            //this.listarArticulo();
            this.modal = 1;
            this.tituloModal = 'Seleccione los articulos que desee';

        },

        desactivarVenta(id) {
            swal({
                title: 'Esta seguro de anular esta venta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/venta/desactivar', {
                        'id': id
                    }).then(function (response) {
                        me.listarVenta(1, '', 'num_comprobante');
                        swal(
                            'Anulado!',
                            'La venta ha sido anulado con éxito.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },
    },

    created() {
        // Realiza una solicitud AJAX para obtener los datos de sesión
        axios.get('/obtener-datos-sesion')
        .then(response => {
            this.scodigorecepcion = response.data.scodigorecepcion;
            console.log('Valor de scodigorecepcion:', this.scodigorecepcion);
            return axios.get('/ruta-a-tu-endpoint-laravel-para-obtener-ultimo-comprobante');
        })
        .then(response => {
            const lastComprobante = response.data.last_comprobante;
            this.last_comprobante = lastComprobante;
            this.nextNumber();
        })
        .catch(error => {
            console.error('Error al obtener datos de sesión o el último comprobante:', error);
        });
    },


    mounted() {
        this.listarVenta(1, this.buscar, this.criterio);
        window.addEventListener('keydown', this.atajoButton);
        this.obtenerDatosUsuario();
        //this.listarArticulo(1, this.buscar, this.criterio);
        this.listarMenu(this.buscar, this.criterio);
        this.getCategoriasMenu();
        this.getCategoriasProductos();

        this.updateButtonStyle();
        window.addEventListener('resize', this.updateButtonStyle);
    },

    beforeDestroy() {
        window.removeEventListener('resize', this.updateButtonStyle);
    }
}
</script>

<style >

/* Sidebar */
.p-panel .p-panel-header {
    padding: 10px;
}

.p-panel .p-panel-content {
    padding: 10px 3px;
}
/* --------------------------------------------*/

/* Botones -> Card */
.project-card {
    border: 2px solid #ccc;
    border-radius: 25px;
    padding: 0px;
    margin-bottom: 0px;
    cursor: pointer;

    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.project-card:hover {
    border-color: #3b82f6;
}

.p-button {
    padding: 0px;
}

.p-card .p-card-content {
    padding: 0px;
}

.p-card .p-card-body {
    padding: 2px;
}

.p-card .p-card-footer {
    padding: 0px 1px 1px 1px;
}

.product-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-button {
    border: 0px solid #ff0000;
    border-radius: 25px;
    padding: 0;
    margin: 0;
}

.image-container {
    overflow: hidden;
    border-radius: 25px 25px 0 0;
}

.product-image {
    width: 100%;
    height: auto;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    padding-top: 0;
    margin-left: 15px;
}

.price {
    margin-top: 10px;
    font-weight: bold;
    font-size: larger;
}

.rounded-bottom-right {
    border-bottom-right-radius: 20px;
}

@media (min-width: 1200px) {
    .product-container {
        width: calc(100% / 8);
    }
}

@media (max-width: 1199px) and (min-width: 992px) {
    .product-container {
        width: calc(100% / 6);
    }
}

@media (max-width: 991px) and (min-width: 768px) {
    .product-container {
        width: calc(100% / 4);
    }
}

@media (max-width: 767px) {
    .product-container {
        width: calc(100% / 2);
    }
}

.floating-buttons {
    position: fixed;
    bottom: 90px;
    right: 20px;
    z-index: 1000;
}

.floating-button {
    padding: 25px 35px;
    border: none;
    border-radius: 8px;
    font-size: 28px;
    cursor: pointer;
}

/* Sidebar full*/
.sidebar-full {
    padding-right: 20px;
}

</style>
