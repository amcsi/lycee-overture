<template>
    <div>
        <h2>{{ $t('rules.costRulesTitle') }}</h2>

        <h3>{{ $t('rules.cost.title') }}</h3>

        <p>
            {{ $t('rules.cost.description1') }}<br />
            {{ $t('rules.cost.description2') }}
        </p>

        <ul>
            <li>{{ $t('rules.cost.point1') }}</li>
            <li>{{ $t('rules.cost.point2') }}</li>
        </ul>

        <ImageWithAnatomy
            src="https://lycee-tcg.com/rule/images/index_4_img1.png"
            :alt="$t('rules.characterCard.imageAlt')"
        >
            <AnatomyRow only-name number="1" :name="$t('rules.costCard.element')" />
            <AnatomyRow only-name number="2" name="EX" />
            <AnatomyRow only-name number="3" :name="$t('rules.costCard.cost')" />
        </ImageWithAnatomy>

        <p>In progress...</p>
    </div>
</template>

<script>
  import AnatomyRow from './AnatomyRow';
  import ImageWithAnatomy from './ImageWithAnatomy';

  /** @class CostRules */
  export default {
    name: 'CostRules',
    components: { AnatomyRow, ImageWithAnatomy },
  };
</script>

<style scoped>

</style>
