<script>
import axios from 'axios';
import moment from 'moment';

export default {
  data() {
    return {
      isAddNewFormValid: false,
      isEditFormValid: false,
      cards: {
        data: [],
        current_page: 1,
        per_page: 10,
        total: 0,
     },
      editDialogVisible: false,
      addNewDialogVisible: false,
      editForm: {
        card_number: '',
        pin: '',
        activation_date: '',
        expiry_date: '',
        balance: '',
      },
      addNewForm: {
        card_number: '',
        pin: '',
        activation_date: '',
        expiry_date: '',
        balance: '',
      },
      editRules: {
        card_number: [
          {required: true, message: 'Card number is required', trigger: 'blur'},
          {validator: this.validateCardNumber, trigger: 'blur'},
        ],
        pin: [
          {required: true, message: 'PIN is required', trigger: 'blur'},
          {validator: this.validatePin, trigger: 'blur'},
        ],
        activation_date: [
          {required: true, message: 'Activation date is required', trigger: 'blur'},
        ],
        expiry_date: [
          {required: true, message: 'Expiry date is required', trigger: 'blur'},
        ],
        balance: [
          {required: true, message: 'Balance is required', trigger: 'blur'},
        ],
      },
    };
  },
  mounted() {
    this.loadData();
  },
  computed: {
    isLoggedIn() {
      return this.$store.state.isLoggedIn;
    },
  },
  methods: {
    async loadData(page = 1) {
      axios.get(`/api/cards?page=${page}`)
          .then(response => (this.cards = response.data))
          .catch(error => console.error(error));
    },
    handleEditForm(index, row) {
      this.editForm = {...row};
      this.editDialogVisible = true;
    },
    async submitEditForm() {
      console.log('Token:', this.$store.state.token);
      const headers = {
        Authorization: `Bearer ${this.$store.state.token}`,
      };
      const formattedActivationDate = moment(String(this.editForm.activation_date)).format('YYYY-MM-DD HH:mm:ss')
      const formattedExpiryDate = moment(String(this.editForm.expiry_date)).format('YYYY-MM-DD')
      const {id, ...dataToSend} = {
        ...this.editForm,
        activation_date: formattedActivationDate,
        expiry_date: formattedExpiryDate,
      };
      this.$refs.editForm.validate((valid) => {
        this.isEditFormValid = valid;
      });
      if (!this.isEditFormValid) {
        return;
      }
      axios.put(`/api/cards/${this.editForm.id}`, dataToSend, {headers})
          .then(response => {
            this.editDialogVisible = false;
            this.loadData();
            this.resetEditForm();
          })
          .catch(error => console.error(error));
    },
    handleDelete(index, row) {
      axios.delete(`/api/cards/${row.id}`, {headers})
          .then(response => {
            this.loadData();
          })
          .catch(error => console.error(error));
    },
    submitAddNewForm() {
      const headers = {
        Authorization: `Bearer ${this.$store.state.token}`,
      };
      const formattedActivationDate = moment(String(this.addNewForm.activation_date)).format('YYYY-MM-DD HH:mm:ss')
      const formattedExpiryDate = moment(String(this.addNewForm.expiry_date)).format('YYYY-MM-DD')
      const {id, ...dataToSend} = {
        ...this.addNewForm,
        activation_date: formattedActivationDate,
        expiry_date: formattedExpiryDate,
      };
      this.$refs.addNewForm.validate((valid) => {
        this.isAddNewFormValid = valid;
      });
      if (!this.isAddNewFormValid) {
        return;
      }
      axios.post('/api/cards', dataToSend, {headers})
          .then(response => {
            this.addNewDialogVisible = false;
            this.loadData();
            this.resetAddNewForm();
          })
          .catch(error => console.error(error));
    },
    resetEditForm() {
      this.$refs.editForm.resetFields();
      this.editDialogVisible = false;
    },
    resetAddNewForm() {
      this.$refs.addNewForm.resetFields();
      this.addNewDialogVisible = false;
    },
    validateCardNumber(rule, value, callback) {
      const cardNumberRegex = /^\d{20}$/;
      if (value && !cardNumberRegex.test(value)) {
        callback(new Error('Card number must be 20 digits'));
      } else {
        callback();
      }
    },
    validatePin(rule, value, callback) {
      const pinRegex = /^\d{4}$/;
      if (value && !pinRegex.test(value)) {
        callback(new Error('PIN must be 4 digits'));
      } else {
        callback();
      }
    },
    handleCurrentChange(val) {
      this.loadData(val);
    }
  }
};
</script>

<template>
  <div>
    <el-button v-if="isLoggedIn" type="primary" @click="addNewDialogVisible = true">Add New Card</el-button>
    <el-table :data="cards.data" style="width: 100%">
      <el-table-column prop="card_number" label="Card number" width="200"/>
      <el-table-column prop="pin" label="PIN" width="180"/>
      <el-table-column prop="activation_date" label="Activation date" width="180"/>
      <el-table-column prop="expiry_date" label="Expiry date" width="180"/>
      <el-table-column prop="balance" label="Balance" width="180"/>
      <el-table-column v-if="isLoggedIn" label="Operations" width="180">
        <template #default="scope">
          <el-button size="small" @click="handleEditForm(scope.$index, scope.row)">Edit</el-button>
          <el-button size="small" type="danger" @click="handleDelete(scope.$index, scope.row)">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
        @current-change="handleCurrentChange"
        :current-page="cards.current_page"
        :page-sizes="[10, 20, 50]"
        :page-size="cards.per_page"
        :total="cards.total">
    </el-pagination>

    <el-dialog v-model="editDialogVisible" title="Edit Form">
      <el-form :model="editForm" ref="editForm" :rules="editRules">
        <el-form-item label="Card number" prop="card_number">
          <el-input maxlength="20" type="text" v-model="editForm.card_number"></el-input>
        </el-form-item>
        <el-form-item label="PIN" prop="pin">
          <el-input maxlength="4" type="text" v-model="editForm.pin"></el-input>
        </el-form-item>
        <el-form-item label="Activation date" prop="activation_date">
          <el-date-picker type="datetime" format="YYYY-MM-DD HH:mm:ss"
                          v-model="editForm.activation_date"></el-date-picker>
        </el-form-item>
        <el-form-item label="Expiry date" prop="expiry_date">
          <el-date-picker type="date" format="YYYY-MM-DD" v-model="editForm.expiry_date"></el-date-picker>
        </el-form-item>
        <el-form-item label="Balance" prop="balance">
          <el-input v-model="editForm.balance"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitEditForm">Save</el-button>
          <el-button @click="resetEditForm">Cancel</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>

    <el-dialog v-model="addNewDialogVisible" title="Add New Card">
      <el-form :model="addNewForm" ref="addNewForm" :rules="editRules">
        <el-form-item label="Card number" prop="card_number">
          <el-input type="number" v-model="addNewForm.card_number"></el-input>
        </el-form-item>
        <el-form-item label="PIN" prop="pin">
          <el-input type="number" v-model="addNewForm.pin"></el-input>
        </el-form-item>
        <el-form-item label="Activation date" prop="activation_date">
          <el-date-picker type="datetime" format="YYYY-MM-DD HH:mm:ss"
                          v-model="addNewForm.activation_date"></el-date-picker>
        </el-form-item>
        <el-form-item label="Expiry date" prop="expiry_date">
          <el-date-picker type="date" format="YYYY-MM-DD" v-model="addNewForm.expiry_date"></el-date-picker>
        </el-form-item>
        <el-form-item label="Balance" prop="balance">
          <el-input v-model="addNewForm.balance"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitAddNewForm">Save</el-button>
          <el-button @click="resetAddNewForm">Cancel</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>