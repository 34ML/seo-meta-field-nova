<template>
  <DefaultField :field="field" :errors="errors">
    <template #field v-if="ready">
          <div v-for="(locale,index) in availableLocales" :key="index">
            <div class="form-group mb-3">
                <label class="mb-1 block">Title [{{locale}}]:</label>
                <input
                    :id="field.name + locale + '-title'"
                    type="text"
                    class="w-full form-control form-input form-input-bordered"
                    :class="errorClasses"
                    :placeholder="field.name[locale]"
                    v-model="value.title[locale]"
                    @change="setHasChanged"
                    @input="setHasChanged"
                />
                <p
                    class="help-block"
                    v-if="field.title_format && field.title_format !== ':text'"
                >{{ field.title_format.replace(':text', value.title[locale] || '') }}</p>
            </div>
            <div class="form-group mb-3">
                <label class="mb-1 block">Description [{{locale}}]:</label>
                <textarea
                    class="w-full form-control form-input form-input-bordered py-3 h-auto"
                    :id="field.name + locale + '-description'"
                    placeholder="Enter SEO description"
                    v-model="value.description[locale]"
                    @input="setHasChanged"
                />
            </div>
            <div class="form-group mb-3">
                <label class="mb-1 block">Keywords [{{locale}}]:</label>
                <textarea
                    class="w-full form-control form-input form-input-bordered py-3 h-auto"
                    :id="field.name + locale + '-keywords'"
                    placeholder="separate between keywords by ', ' ex : keyword1, keyword2"
                    v-model="value.keywords[locale]"
                    @input="setHasChanged"
                />
            </div>
          </div>
            <div class="form-group mb-3">
                <label class="mb-1 block">Follow:</label>
                <SelectControl
                    :id="field.name + '-follow'"
                    :selected="value.follow_type"
                    class="w-full"
                    :options="followOptions"
                    @change="value.follow_type = $event;setHasChanged()"
                >
                    <option value selected>
                        {{
                        __('Choose an option')
                        }}
                    </option>
                </SelectControl>
            </div>
            <div class="form-group mb-3">
                <label class="mb-1 block">Image:</label>
                <seo-media
                    :value="field.image_url"
                    :file="imageFile"
                    @imageSelect="uploadImage"
                ></seo-media>
            </div>
        </template>
  </DefaultField>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ["resourceName", "resourceId", "field"],

    data() {
        const field = this.field;

        return {
            hasChanged: false,
            imageFile: null,
            followOptions:
                field && field.follow_type_options
                    ? Object.keys(field.follow_type_options).map(value => ({
                          value,
                          label: field.follow_type_options[value]
                      }))
                    : [],
          availableLocales:field.available_locales,
          ready:false
        };
    },
    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || this.field.default_value;
            this.ready = true;
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(
                this.field.attribute,
                this.value ? JSON.stringify(this.value, function replacer(key, value) {
                  if (Array.isArray(value) && value.length === 0) {
                    return { ...value }; // Converts empty array with string properties into a POJO
                  }
                  return value;
                }) : ""
            );
            if (this.imageFile) {
                formData.append(
                    this.field.attribute + "_image",
                    this.imageFile
                );
            }
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value;
        },

        /**
         * Has changed input
         */
        setHasChanged() {
            this.hasChanged = true;
        },
      uploadImage(file) { // TODO check why the image is not being sent
        this.imageFile = file;
        this.setHasChanged();
      }

    }
};
</script>

<style lang="scss" scoped>
.help-block {
    font-size: 16px;
    margin: 6px 0 0;
    font-style: italic;
}
</style>
