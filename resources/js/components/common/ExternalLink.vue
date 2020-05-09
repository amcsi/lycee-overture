<template>
    <a target="_blank" rel="nofollow noopener" :href="href">
        <slot></slot>
    </a>
</template>

<script>
  /** @class ExternalLink */
  export default {
    name: 'ExternalLink',
    props: {
      href: {
        type: String,
        required: true,
      },
    },
  };
</script>
