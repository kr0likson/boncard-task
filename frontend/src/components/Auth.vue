<template>
  <div>
    <div style="margin-bottom: 20px;">
      <div v-if="isLoggedIn">
        <div>
          Hello, {{ $store.state.user.name }}!
        </div>
        <div>
          <el-button type="primary" @click="logout">Logout</el-button>
        </div>
      </div>
      <div v-else>
        <el-button type="primary" @click="loginDialogVisible = true">Login</el-button>
        <el-button type="primary" @click="registerDialogVisible = true">Register</el-button>
      </div>
    </div>
    <el-dialog v-model="loginDialogVisible" title="Login Form">
      <div v-if="loginErrorMessage" style="color: red;">{{ loginErrorMessage }}</div>
      <el-form
          label-position="top"
          label-width="100px"
          :model="userLoginData"
          style="max-width: 460px"
      >
        <el-form-item label="Email">
          <el-input v-model="userLoginData.email" required/>
        </el-form-item>
        <el-form-item label="Password">
          <el-input type="password" v-model="userLoginData.password" required/>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="loginUser">Login</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>

    <el-dialog v-model="registerDialogVisible" title="Register form">
      <div v-if="registerErrorMessage" style="color: red;">{{ registerErrorMessage }}</div>
      <el-form
          label-position="top"
          label-width="100px"
          :model="userRegisterData"
          style="max-width: 460px"
      >
        <el-form-item label="Name">
          <el-input v-model="userRegisterData.name" required/>
        </el-form-item>
        <el-form-item label="Email">
          <el-input v-model="userRegisterData.email" required/>
        </el-form-item>
        <el-form-item label="Password">
          <el-input type="password" v-model="userRegisterData.password" required/>
        </el-form-item>
        <el-form-item label="Confirm password">
          <el-input type="password" v-model="userRegisterData.password_confirmation" required/>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="register">Register</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userLoginData: {
        email: '',
        password: '',
      },
      userRegisterData: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
      loginDialogVisible: false,
      registerDialogVisible: false,
      loginErrorMessage: '',
      registerErrorMessage: ''
    };
  },
  computed: {
    isLoggedIn() {
      return this.$store.state.isLoggedIn;
    },
  },
  methods: {
    async register() {
      try {
        const response = await this.$store.dispatch('register', this.userRegisterData);
        console.log('Registration successful');
        await this.loginUser();
        this.registerDialogVisible = false;
      } catch (error) {
        this.registerErrorMessage = 'Registration failed: ' + error.response.data.message;
      }
    },
    async loginUser() {
      try {
        const response = await this.$store.dispatch('login', this.userLoginData);
        this.loginDialogVisible = false;
        console.log('Login successful');
      } catch (error) {
        this.loginErrorMessage = 'Login failed: ' + error.response.data.message;
      }
    },
    async logout() {
      this.$store.dispatch('logout');
      console.log('Logged out');

    },
  },
};
</script>

<style scoped>
</style>
