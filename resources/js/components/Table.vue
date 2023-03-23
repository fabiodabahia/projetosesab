<template>
    <div>
        <table class="table table-hover table-bordered table-condensed table-responsive text-center">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in titulos" :key="key">{{t.titulo}}</th>
                    <th v-if="detalhar.visivel || editar.visivel || excluir.visivel">Ação</th>
                </tr>
                
            </thead>
            <tbody>
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto'">{{valor}}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">
                            {{ '...'+valor}}
                        </span>
                    </td>
                    <td v-if="detalhar.visivel || editar.visivel || excluir.visivel">
                        <button v-if="detalhar.visivel" class="btn btn-outline-primary btn-sm" :data-toggle="detalhar.dataToggle" :data-target="detalhar.dataTarget" @click="setStore(obj)">Detalhar</button>
                        <button v-if="editar.visivel" class="btn btn-outline-primary btn-sm" :data-toggle="editar.dataToggle" :data-target="editar.dataTarget" @click="setStore(obj)">Editar</button>
                        <button v-if="excluir.visivel" class="btn btn-outline-danger btn-sm" :data-toggle="excluir.dataToggle" :data-target="excluir.dataTarget" @click="setStore(obj)">Excluir</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos', 'editar', 'detalhar', 'excluir'],
        methods: {
            setStore(obj) {
                console.log(obj)
                this.$store.state.item = obj
            }
        },
        computed: {
            dadosFiltrados() {
                console.log(this.dados)
                
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []

                this.dados.map((item, chave) => {

                    let itemFiltrado = {}
                    campos.forEach(campo => {
                        
                        itemFiltrado[campo] = item[campo] //utilizar a sintaxe de array para atribuir valores a objetos
                    })
                    dadosFiltrados.push(itemFiltrado)
                })

                return dadosFiltrados //retorne um array de objetos 
            }
        }
    }
</script>
