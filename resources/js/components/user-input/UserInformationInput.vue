<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Insert user information</div>

          <div class="card-body">
            <form @submit.prevent="submitForm" enctype="multipart/form-data">
              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                <div class="col-md-6">
                  <input
                    v-model="form.name"
                    id="name"
                    type="text"
                    class="form-control"
                    name="name"
                    autofocus
                    placeholder="Jenny Fields"
                    ref="name"
                    :class="{ 'is-invalid': hasErrors('name') }"
                    @keydown="clearError('name')"
                  />

                  <div
                    v-if="hasErrors('name')"
                    class="invalid-feedback"
                    role="alert"
                  >{{ errors.name[0] }}</div>
                </div>
              </div>

              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                <div class="col-md-6">
                  <input
                    v-model="form.email"
                    id="email"
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': hasErrors('email') }"
                    name="email"
                    placeholder="jenny@example.com"
                    @keydown="clearError('email')"
                  />

                  <div
                    v-if="hasErrors('email')"
                    class="invalid-feedback"
                    role="alert"
                  >{{ errors.email[0] }}</div>
                </div>
              </div>

              <div class="form-group row">
                <label for="phone_number" class="col-md-4 col-form-label text-md-right">Phone number</label>

                <div class="col-md-6">
                  <input
                    v-model="form.phone_number"
                    id="phoneNumber"
                    type="tel"
                    class="form-control"
                    name="phone_number"
                    autofocus
                    placeholder="(123) 456-7890"
                    maxlength="16"
                    :class="{ 'is-invalid': hasErrors('phone_number') }"
                    @keydown="clearError('phone_number')"
                    @input="enforcePhoneFormat()"
                  />

                  <div
                    v-if="hasErrors('phone_number')"
                    class="invalid-feedback"
                    role="alert"
                  >{{ errors.phone_number[0] }}</div>
                </div>
              </div>

              <div class="form-group row">
                <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>

                <div class="col-md-6">
                  <input
                    v-model="form.address"
                    id="address"
                    type="text"
                    class="form-control"
                    name="address"
                    autofocus
                    placeholder="123 Larastreet"
                    :class="{ 'is-invalid': hasErrors('address') }"
                    @keydown="clearError('address')"
                  />

                  <div
                    v-if="hasErrors('address')"
                    class="invalid-feedback"
                    role="alert"
                  >{{ errors.address[0] }}</div>
                </div>
              </div>

              <div class="form-group row">
                <label for="zip_code" class="col-md-4 col-form-label text-md-right">Zip Code</label>

                <div class="col-md-6">
                  <input
                    v-model="form.zip_code"
                    id="zip_code"
                    type="text"
                    class="form-control"
                    name="zip_code"
                    autofocus
                    placeholder="12345"
                    :class="{ 'is-invalid': hasErrors('zip_code') }"
                    @keydown="clearError('zip_code')"
                  />

                  <div
                    v-if="hasErrors('zip_code')"
                    class="invalid-feedback"
                    role="alert"
                  >{{ errors.zip_code[0] }}</div>
                </div>
              </div>

              <photo-input-component @photoChosen="attachPhoto" ref="photoInput"></photo-input-component>

              <license-document-input-component
                @licenseDocumentChosen="attachLicense"
                ref="licenseInput"
              ></license-document-input-component>

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PhotoInput from "./PhotoInputComponent.vue";
import LicenseDocument from "./LicenseDocumentInputComponent.vue";

export default {
  components: {
    "photo-input-component": PhotoInput,
    "license-document-input-component": LicenseDocument
  },

  data() {
    return {
      form: {
        name: "",
        email: "",
        phone_number: "",
        address: "",
        zip_code: "",
        photo: "",
        license_document: ""
      },

      errors: {}
    };
  },

  watch: {
    errors() {
      if (this.hasErrors("photo")) {
        this.$refs.photoInput.hasError(this.errors.photo[0]);
      }

      if (this.hasErrors("license_document")) {
        this.$refs.licenseInput.hasError(this.errors.license_document[0]);
      }
    }
  },

  methods: {
    submitForm() {
      axios
        .post("/api/user-information", this.formData())
        .then(response => {
          this.clearForm();
          this.$toasted.show(response.data);
        })
        .catch(errors => {
          this.recordErrors(errors.response.data.errors);
        });
    },

    formData() {
      let data = new FormData();

      data.append("name", this.form.name);
      data.append("email", this.form.email);
      data.append("phone_number", this.form.phone_number);
      data.append("address", this.form.address);
      data.append("zip_code", this.form.zip_code);
      data.append("photo", this.form.photo);
      data.append("license_document", this.form.license_document);

      return data;
    },

    clearForm() {
      const form = this.form;

      Object.keys(form).forEach(function(key) {
        form[key] = "";
      });

      this.$refs.photoInput.clearInput();
      this.$refs.licenseInput.clearInput();
      this.$refs.name.focus();
    },

    recordErrors(errors) {
      this.errors = errors;
    },

    hasErrors(prop) {
      return this.errors.hasOwnProperty(prop);
    },

    clearError(field) {
      if (this.errors[field]) {
        delete this.errors[field];
      }
    },

    attachPhoto(photo) {
      this.form.photo = photo;
      this.clearError("photo");
    },

    attachLicense(license) {
      this.form.license_document = license;
      this.clearError("license");
    },

    enforcePhoneFormat() {
      let x = this.form.phone_number
        .replace(/\D/g, "")
        .match(/(\d{0,3})(\d{0,3})(\d{0,4})/);

      this.form.phone_number = !x[2]
        ? x[1]
        : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
    }
  }
};
</script>
