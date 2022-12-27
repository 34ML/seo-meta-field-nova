<template>
  <span class="inline-block rounded-full w-2 h-2" :class="seoStatus"></span>
</template>

<script>
export default {
  props: ["resourceName", "field"],
  computed: {
    seoStatus() {
      const field = this.field;
      const value = this.field.value;
      let hasTitleForAnyLocale = false;
      let hasDescriptionForAnyLocale = false;
      let hasKeywordsForAnyLocale = false;
      let hasFollow = false;

      for (const locale in value.title) {
        if (value.title[locale] && value.title[locale].trim() !== ''){
          hasTitleForAnyLocale = true;
        }
      }

      for (const locale in value.description) {
        if (value.description[locale] && value.description[locale].trim() !== ''){
          hasDescriptionForAnyLocale = true;
        }
      }

      for (const locale in value.keywords) {
        if (value.keywords[locale] && value.keywords[locale].trim() !== ''){
          hasKeywordsForAnyLocale = true;
        }
      }

      if(["index, follow", "index, nofollow"].indexOf(value.follow_type) >= 0) {
        hasFollow = true;
      }

      if (hasTitleForAnyLocale && hasDescriptionForAnyLocale && hasKeywordsForAnyLocale && hasFollow) {
        return "bg-green-500";
      }

      if(!hasTitleForAnyLocale && !hasDescriptionForAnyLocale && !hasKeywordsForAnyLocale && !hasFollow) {
        return "bg-red-500";
      }

      return "bg-yellow-500";
    }
  }
};
</script>
