<template>
    <div>
        <h3 class="title grey--text">Machines</h3>
        <v-divider class="my-4"></v-divider>
        <v-btn class="ml-0 success" @click="addMachine">Add machine</v-btn>
        <v-card>
            <v-data-table hide-actions :items="items" :headers="headers" :loading="loading">
                <template v-slot:items="props">
                    <td>{{props.item.machine_name}}</td>
                    <td>{{props.item.machine_type}}</td>
                    <td>{{props.item.ip_address}}</td>
                    <td>
                        <v-btn icon small @click="editMachine(props.item)">
                            <v-icon small>edit</v-icon>
                        </v-btn>
                        <v-btn icon small @click="deleteMachine(props.item)" :loading="props.item.isDeleting">
                            <v-icon small>delete</v-icon>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [],
            headers: [
                {
                    text: 'Machine name',
                    sortable: false
                },
                {
                    text: 'Machine type',
                    sortable: false
                },
                {
                    text: 'IP Address',
                    sortable: false
                },
                {
                    text: 'Actions',
                    sortable: false
                }
            ],
            loading: false,
            activeMachine: null,
            openMachineDialog: false
        }
    },
    methods: {
        load() {
            this.loading = true;
            axios.get('/api/all/machines').then((res, rej) => {
                this.items = res.data.machines;
                this.loading = false;
            }).catch(err => {
                this.loading = false;
            });
        },
        addMachine() {
            this.activeMachine = null;
            this.openMachineDialog = true;
        },
        editMachine(machine) {
            this.activeMachine = machine;
            this.openMachineDialog = true;
        },
        saveMachine(data) {
            if(data.mode == 'insert') {
                this.items.push(data.machine);
            } else {
                this.activeMachine.ip_address = data.machine.ip_address;
                this.activeMachine.machine_name = data.machine.machine_name;
                this.activeMachine.machine_type = data.machine.machine_type;
            }
        },
        deleteMachine(machine) {
            if(confirm("Delete this machine?")) {
                Vue.set(machine, 'isDeleting', true);
                this.$store.dispatch('machine/deleteMachine', machine.id).then((res, rej) => {
                    this.items = this.items.filter(m => m.id != machine.id);
                }).finally(() => {
                    Vue.set(machine, 'isDeleting', false);
                });
            }
        }
    },
    created() {
        this.load();
    }
}
</script>
