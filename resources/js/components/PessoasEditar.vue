<template>
<div class="editar" id="editarp" titulo="editarp">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    
                    <div class="card-header">Editar Pessoas</div>

                    <div class="card-body">  
                                                      
            
                <div class="form-group ">                
                
                    <template name="alertas">
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso" v-if="transacaoStatus == 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a Pessoa" v-if="transacaoStatus == 'erro'"></alert-component>
                    </template>

                    

                    <input-container-component titulo="Nome da pessoa" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da pessoa">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da pessoa" v-model="nomePessoa">
                    </input-container-component>

                    <input-container-component titulo="CPF da pessoa" id="novoCpf" id-help="novoCpfHelp" texto-ajuda="Informe o CPF da pessoa">
                        <input type="text" class="form-control" id="novoCpf" aria-describedby="novoCpfHelp" placeholder="CPF da pessoa" v-model="cpfPessoa">
                    </input-container-component>

                    <input-container-component titulo="E-mail da pessoa" id="novoEmail" id-help="novoEmailHelp" texto-ajuda="Informe o e-mail da pessoa">
                        <input type="text" class="form-control" id="novoEmail" aria-describedby="novoEmailHelp" placeholder="E-mail da pessoa" v-model="emailPessoa">
                    </input-container-component>

                    <input-container-component titulo="Perfil da pessoa" id="novoPerfil" id-help="novoPerfilHelp" texto-ajuda="Informe o perfil da pessoa">
                        <input type="text" class="form-control" id="novoPerfil" aria-describedby="novoPerfilHelp" placeholder="Perfil da pessoa" v-model="perfilPessoa">                        
                    </input-container-component>


                    
                    
                </div>   
                    
                
            
                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="voltar()" >Voltar</button>
                <button type="button" class="btn btn-danger" @click="editar()" >Editar</button>
        
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            voltar(){
                window.location.href = "http://localhost:8000/home";
            },
            
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