<script setup lang="ts">
import Modal from '../../components/Modal.vue'
import { defineProps, reactive, ref, onMounted } from 'vue'
import { useEmployeesStore } from '@/stores/employees.store';
import { useRestaurantsStore } from '@/stores/restaurants.store';
import { storeToRefs } from 'pinia';
import { Form, Field, type FormActions, FieldArray } from 'vee-validate';
import * as Yup from 'yup';

//defineProps({
//  msg: String
//})

// Fetch data
const employeesStore = useEmployeesStore();
const { employees } = storeToRefs(employeesStore);
const restaurantsStore = useRestaurantsStore();
const { restaurants } = storeToRefs(restaurantsStore);
const initialize = async () => {
  await employeesStore.getAll();
  await restaurantsStore.getAll();
};
onMounted(() => initialize());

// Modal
const closeableModal = ref(false);

// Add Employee form
const schemaAddEmployee = Yup.object().shape({
  firstname: Yup.string()
      .required('Firstname is required'),
  lastname: Yup.string()
      .required('Firstname is required'),
  email: Yup.string()
      .required('Email is required')
  //restaurant_ids: Yup.array()
  //    .required('Restaurants is required')
});

async function onSubmitAddEmployee(values : any, actions) {
  try {
    await employeesStore.register(values);
    closeableModal.value = false;
    await initialize();
  } catch (error : any) { 
    console.log('AddEmployeeError', error);
  }
}
</script>

<template>
  <div>
    <div class="card m-3">
      <h4 class="card-header">
        <button @click="closeableModal = true" type="button" class="btn btn-primary">Add employee</button>
      </h4>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Firstname</th>
                <th scope="col">Lastname</th>
                <th scope="col">Email</th>
                <th scope="col">Restaurants</th>
                <th scope="col">Handle</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(employee, index) in employees">
                <th scope="row">{{index+1}}</th>
                <td>{{employee.firstname}}</td>
                <td>{{employee.lastname}}</td>
                <td>{{employee.email}}</td>
                <td>
                  <label class="bg-success rounded me-3 p-1" v-for="restaurant in employee.restaurants">{{restaurant.name}}</label>
                </td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <Modal v-model="closeableModal" closeable header="Add employee">
      <Form @submit="onSubmitAddEmployee" :validation-schema="schemaAddEmployee" v-slot="{ errors, isSubmitting }">
        <div class="form-group">
            <label>Firtname</label>
            <Field name="firstname" type="text" class="form-control" :class="{ 'is-invalid': errors.firstname }" />
            <div class="invalid-feedback">{{ errors.firstname }}</div>
        </div>
        <div class="form-group">
            <label>Lastname</label>
            <Field name="lastname" type="text" class="form-control" :class="{ 'is-invalid': errors.lastname }" />
            <div class="invalid-feedback">{{ errors.lastname }}</div>
        </div>
        <div class="form-group">
            <label>Email</label>
            <Field name="email" type="text" class="form-control" :class="{ 'is-invalid': errors.email }" />
            <div class="invalid-feedback">{{ errors.email }}</div>
        </div>
        <div class="form-group">
            <label>Restaurants</label>
            <div class="fomr-control">
              <span class="ms-3" v-for="restaurant in restaurants">

                <Field v-slot="{ field }" name="restaurant_ids" type="checkbox" :value="restaurant.id" :unchecked-value="false">
                  <label>
                    <input type="checkbox" name="restaurant_ids" v-bind="field" :value="restaurant.id" />
                    {{restaurant.name}}
                  </label>
                </Field>
              </span>
            </div>
            <div class="invalid-feedback">{{ errors.restaurant_ids }}</div>
        </div>
        <div class="form-group">
            <label>Note</label>
            <Field name="note" type="textarea" class="form-control" :class="{ 'is-invalid': errors.note }" />
            <div class="invalid-feedback">{{ errors.note }}</div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary mt-3" :disabled="isSubmitting">
                <span v-show="isSubmitting" class="spinner-border spinner-border-sm mr-1"></span>
                Add
            </button>
        </div>
      </Form>
    </Modal> 

  </div>
</template>