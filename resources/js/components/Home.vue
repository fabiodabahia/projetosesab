<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                
                
                <!-- início do card de busca -->
                <card-component titulo="Busca de pessoas">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            
                            <div class="col mb-3">
                                <input-container-component titulo="Nome" id="inputNome" id-help="nomeHelp">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Digite o nome pessoa" v-model="busca.nome">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="CPF" id="inputCPF" id-help="CPFHelp">
                                    <input type="number" class="form-control" id="inputCPF" aria-describedby="idHelp" placeholder="Digite o CPF" v-model="busca.CPF">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- fim do card de busca -->


                <!-- início do card de listagem de pessoas -->
                <card-component titulo="Relação de pessoas">
                    <template v-slot:conteudo>
                            <table-component
                            :dados="pessoas.data"
                            :detalhar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalPessoaDetalhar'}"
                            :editar="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalPessoaEditar'}"
                            :excluir="{ visivel: true, dataToggle: 'modal', dataTarget: '#modalPessoaExcluir'}"
                            :titulos="{
                            id:{titulo:'ID', tipo: 'texto'},
                            created_at:{titulo:'Data de cadastro', tipo: 'data'},
                            nome:{titulo:'Nome', tipo: 'texto'},
                            CPF:{titulo:'CPF',tipo: 'texto'},
                            email:{titulo:'E-mail',tipo: 'texto'},
                            perfil:{titulo:'Perfil', tipo: 'texto'},
                            }"
                            
                        ></table-component>
                    </template>                


                   <template v-slot:rodape>
                    <paginate-component>
                        <li v-for="l, key in pessoas.links" :key="key"
                         :class="l.active ? 'page-item active' : 'page-item'"
                         @click="paginacao(l)">
                         <a class="page-link" v-html="l.label"></a>
                         </li>
                    </paginate-component>
                    </template>
                </card-component>
                <!-- fim do card de listagem de pessoas -->
            </div>
        </div>



        <!-- início do modal de detalhar de pessoa -->
        <modal-component id="modalPessoaDetalhar" titulo="Detalhar pessoa">
            <template v-slot:alertas></template>            
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>

                <input-container-component titulo="CPF">
                    <input type="text" class="form-control" :value="$store.state.item.CPF" disabled>
                </input-container-component>

                <input-container-component titulo="Data de Cadastro">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        <!-- fim do modal de detalhar pessoa -->


                <!-- início do modal de excluir de pessoa -->
        <modal-component id="modalPessoaExcluir" titulo="Excluir pessoa">
            <template v-slot:alertas></template>            
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="excluir()" data-dismiss="modal">Excluir</button>
            </template>
        </modal-component>
        <!-- fim do modal de editar pessoa -->

        <!-- início do modal de editar de pessoa -->
        <modal-component id="modalPessoaEditar" titulo="Editar pessoa">
            <template v-slot:alertas></template>            
            <template v-slot:conteudo>
                 <input-container-component titulo="Nome da pessoa" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da pessoa">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da pessoa" v-model="$store.state.item.nome">
                    </input-container-component>

                    <input-container-component titulo="CPF da pessoa" id="novoCpf" id-help="novoCpfHelp" texto-ajuda="Informe o CPF da pessoa">
                        <input type="text" class="form-control" id="novoCpf" aria-describedby="novoCpfHelp" placeholder="CPF da pessoa" v-model="$store.state.item.CPF">
                    </input-container-component>

                <input-container-component titulo="E-mail da pessoa" id="novoEmail" id-help="novoEmailHelp" texto-ajuda="Informe o e-mail da pessoa">
                        <input type="text" class="form-control" id="novoEmail" aria-describedby="novoEmailHelp" placeholder="E-mail da pessoa" v-model="$store.state.item.email">
                    </input-container-component>

                    <input-container-component titulo="Perfil da pessoa" id="novoPerfil" id-help="novoPerfilHelp" texto-ajuda="Informe o perfil da pessoa">
                        <input type="text" class="form-control" id="novoPerfil" aria-describedby="novoPerfilHelp" placeholder="Perfil da pessoa" v-model="$store.state.item.perfil">                        
                    </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="editar()" data-dismiss="modal">Editar</button>
            </template>
        </modal-component>
        <!-- fim do modal de editar pessoa -->
        
    </div>    
</template>


<script>
import Alert from './Alert.vue';
    export default {
  components: { Alert },
        data() {
            return {
            urlBase: 'http://localhost:8000/api/pessoa',
            urlPaginacao: '',
            urlFiltro: '',
            nomePessoa: '',
            cpfPessoa: '', 
            emailPessoa: '', 
            perfilPessoa: '',
            transacaoStatus: '',
            transacaoDetalhes: {},
            pessoas: { data:[]},
            busca: { CPF:'', nome:''}
            }         
        },
        methods: {
            editar() {

                let formData = new FormData();
                formData.append('_method', 'patch')
                formData.append('nome', this.$store.state.item.nome)
                formData.append('CPF', this.$store.state.item.CPF)
                formData.append('email', this.$store.state.item.email)
                formData.append('perfil', this.$store.state.item.perfil)                
                
                let url = this.urlBase + '/' + this.$store.state.item.id

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json'                        
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        console.log('Atualizado', response)
                        this.carregarLista()
                    })
                    .catch(errors => {
                        console.log('Erro de atualização', errors.response)
                    })
            },
            excluir(){

                let formData = new FormData();
                formData.append('_method', 'delete')

                let config = {
                    headers: {
                        'Accept': 'application/json'                        
                    }
                }
                let url = this.urlBase + '/' + this.$store.state.item.id

                console.log(url)
                axios.post(url, formData, config)
                .then(response =>{
                    console.log('removido', response)
                    this.carregarLista()
                })
                
            },
            pesquisar() {
                //console.log(this.busca)

                let filtro = ''

                for(let chave in this.busca) {

                    if(this.busca[chave]) {
                        //console.log(chave, this.busca[chave])
                        if(filtro != '') {
                            filtro += ";"
                        }

                        filtro += chave + ':like:' + this.busca[chave]
                    }
                }
                if(filtro != '') {
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro='+filtro
                } else {
                    this.urlFiltro = ''
                }

                this.carregarLista()
            },
            paginacao(l) {
                if(l.url) {
                    //this.urlBase = l.url //ajustando a url de consulta com o parâmetro de página
                    this.urlPaginacao = l.url.split('?')[1]
                    this.carregarLista() //requisitando novamente os dados para nossa API
                }
            },
            carregarLista() {

                let config = {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }

                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro
                console.log(url)
                axios.get(url, config)
                    .then(response => {
                        this.pessoas = response.data
                        //console.log(this.pessoas)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            
            salvar(){
                console.log(this.nomePessoa,this.cpfPessoa,this.emailPessoa,this.perfilPessoa)

                let formData = new FormData();
                formData.append('nome', this.nomePessoa)
                formData.append('CPF', this.cpfPessoa)
                formData.append('email', this.emailPessoa)
                formData.append('perfil', this.perfilPessoa)

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept' : 'application/json'
                        }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro: ' + response.data.id
                        }

                        console.log(response)
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors
                        }
                        //errors.response.data.message
                    })
            }
        },
        mounted() {
            this.carregarLista()
        }
    }
</script>