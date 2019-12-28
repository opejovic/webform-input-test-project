<template>
  <div class="form-group row">
    <label for="photo" class="col-md-4 col-form-label text-md-right">Photo</label>

    <div class="col-md-6">
      <div class="custom-file">
        <input
          id="photo"
          type="file"
          class="form-control custom-file-input"
          name="photo"
          accept="image/*"
          autofocus
          :class="this.error ? 'is-invalid' : ''"
          @change="onChange"
        />
        <label class="custom-file-label" for="photo">{{ photoName }}</label>
        <div v-if="error" class="invalid-feedback" role="alert">{{ errorMessage }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      photo: "",
      name: "",
      error: false,
      errorMessage: ""
    };
  },

  computed: {
    photoName() {
      return this.name ? this.name : "Chose a photo";
    }
  },

  methods: {
    onChange(e) {
      this.clearErrors();

      if (!e.target.files.length) return;

      let photo = e.target.files[0];

      this.name = photo.name;

      let reader = new FileReader();

      reader.readAsDataURL(photo);

      reader.onload = e => {
        this.photo = e.target.result;
        this.$emit("photoChosen", photo);
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
      this.photo = "";
      this.name = "";
    }
  }
};
</script>