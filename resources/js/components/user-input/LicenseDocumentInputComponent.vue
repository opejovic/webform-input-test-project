<template>
  <div class="form-group row">
    <label for="license_document" class="col-md-4 col-form-label text-md-right">License Document</label>

    <div class="col-md-6">
      <div class="custom-file">
        <input
          id="license_document"
          type="file"
          class="form-control custom-file-input"
          name="license_document"
          autofocus
          :class="this.error ? 'is-invalid' : ''"
          @change="onChange"
        />
        <label class="custom-file-label" for="license_document">{{ documentName }}</label>
        <div v-if="error" class="invalid-feedback" role="alert">{{ errorMessage }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      document: "",
      name: "",
      error: false,
      errorMessage: ""
    };
  },

  computed: {
    documentName() {
      return this.name ? this.name : "Chose a license document";
    }
  },

  methods: {
    onChange(e) {
      this.clearErrors();

      if (!e.target.files.length) return;

      let licenseDocument = e.target.files[0];

      this.name = licenseDocument.name;

      let reader = new FileReader();

      reader.readAsDataURL(licenseDocument);

      reader.onload = e => {
        this.document = e.target.result;
        this.$emit("licenseDocumentChosen", licenseDocument);
      };
    },

    hasError(error) {
      this.error = true;
      this.errorMessage = error;
    },

    clearErrors() {
      this.error = false;
      this.errorMessage = "";
    },

    clearInput() {
      this.document = "";
      this.name = "";
    }
  }
};
</script>