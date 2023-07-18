<template>
    <tr>
        <td><input type="checkbox" :checked="selectAll" @change="toggleSelection"/></td>
        <td>{{ index +1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.formatted_created_at }}</td>
        <!-- <td>{{ moment(user.created_at).format('YYYY-MM-DD') }}</td> -->
        <td>
            <select class="form-control" @change="changeRole(user, $event.target.value)">
                <option v-for="role in roles" :key="role" :value="role.value" :selected="(user.role === role.value)">{{ role.name }}</option>
            </select>
        </td>
        <td>
            <a href="#" @click.prevent="$emit('editUser', user)"><i class="fa fa-edit"></i></a>
            <!-- <a href="#" @click.prevent="editUser(user)"><i class="fa fa-edit"></i></a> -->
            <a href="#" @click.prevent="$emit('confirmUserDeletion',user)"><i class="fa fa-trash text-danger ml-2"></i></a>
            <!-- <a href="#" @click.prevent="confirmUserDeletion(user)"><i class="fa fa-trash text-danger ml-2"></i></a> -->
        </td>
    </tr>
</template>

<script setup>
import { formatDate } from '../../helper.js';
import { ref } from 'vue';
import  { useToastr } from '../../toastr.js';
import axios from 'axios';

const toastr = useToastr();
const props = defineProps({
    user:Object,
    index: Number,
    selectAll: Boolean,
});
const emit = defineEmits(['userDeleted', 'editUser', 'confirmUserDeletion']);

// const editUser = (user) => {
//     emit('editUser', user);
// }

const roles = ref([
    {
        name: 'ADMIN',
        value: 1
    },
    {
        name: 'USER',
        value: 2
    }
]);

const changeRole = (user, role) => {
    axios.patch(`/api/users/${user.id}/change-role`, {
        role:role,
    })
    .then(() => {
        toastr.success('Role changed successfully!');
    })
};

const toggleSelection = () => {
    emit('toggleSelection', props.user);
}

</script>
