<template>
  <section>
    <h2>{{ $t('rules.cost.title') }}</h2>

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

    <p>
      <CardText :text="$t('rules.costExample.example1')"></CardText>
    </p>

    <p>
      <cloudinary-fetch-img
        src="https://lycee-tcg.com/rule/images/index_4_img2.png"
        :alt="$t('rules.costExample.imageAlt')"
      />
    </p>

    <h2>{{ $t('rules.howToSummon.title') }}</h2>

    <p>
      <CardText :text="$t('rules.howToSummon.description')"></CardText>
    </p>

    <p>
      <cloudinary-fetch-img
        src="https://lycee-tcg.com/rule/images/index_4_img3.png"
        :alt="$t('rules.howToSummon.imageAlt')"
      />
    </p>

    <el-card class="card">
      <h3>
        <CardText :text="$t('rules.howToSummon.box.title')" />
      </h3>

      <p class="text-center">
        ①
        <CardText :text="$t('rules.howToSummon.box.option1')" />
        <br />
        {{ $t('rules.howToSummon.box.or') }}<br />
        ②
        <CardText :text="$t('rules.howToSummon.box.option2')" />
      </p>
    </el-card>

    <div class="arrow-pointing-down"></div>

    <div class="discard2-box">
      ①
      <CardText :text="$t('rules.howToSummon.discard2Box')" />
    </div>

    <p>{{ $t('rules.howToSummon.box.or') }}...</p>

    <div class="discard1-box">
      ②
      <CardText :text="$t('rules.howToSummon.discard1Box')" />
    </div>

    <h2>{{ $t('rules.typesOfCosts.title') }}</h2>

    <table class="type-of-costs-table">
      <tr>
        <td>
          <CardText text="[snow]" />
        </td>
        <td>
          <CardText :text="$t('rules.typesOfCosts.oneCostOfSnow')" />
        </td>
        <td>
          <CardText text="[moon]" />
        </td>
        <td>
          <CardText :text="$t('rules.typesOfCosts.oneCostOfMoon')" />
        </td>
        <td>
          <CardText text="[flower]" />
        </td>
        <td>
          <CardText :text="$t('rules.typesOfCosts.oneCostOfFlower')" />
        </td>
      </tr>
      <tr>
        <td>
          <CardText text="[space]" />
        </td>
        <td>
          <CardText :text="$t('rules.typesOfCosts.oneCostOfSpace')" />
        </td>
        <td>
          <CardText text="[sun]" />
        </td>
        <td>
          <CardText :text="$t('rules.typesOfCosts.oneCostOfSun')" />
        </td>
        <td>
          <CardText text="[star]" />
        </td>
        <td>{{ $t('rules.typesOfCosts.oneCostOfStar') }}</td>
      </tr>
      <tr>
        <td>
          <CardText text="[T]" />
        </td>
        <td colspan="5">{{ $t('rules.typesOfCosts.tap') }}</td>
      </tr>
      <tr>
        <td>
          <CardText text="[D1]" />
        </td>
        <td colspan="5">{{ $t('rules.typesOfCosts.discardFromDeck') }}</td>
      </tr>
      <tr>
        <td>{{ $t('rules.typesOfCosts.other.title') }}</td>
        <td colspan="5">{{ $t('rules.typesOfCosts.other.description') }}</td>
      </tr>
      <tr>
        <td>
          <CardText text="[0]" />
        </td>
        <td colspan="5">{{ $t('rules.typesOfCosts.free') }}</td>
      </tr>
    </table>

    <h2>{{ $t('rules.assist.title') }}</h2>

    <p>{{ $t('rules.assist.description') }}</p>
  </section>
</template>

<script>
import CardText from '../card/CardText';
import CloudinaryFetchImg from '../common/CloudinaryFetchImg';
import AnatomyRow from './AnatomyRow';
import ImageWithAnatomy from './ImageWithAnatomy';

/** @class CostRules */
export default {
  name: 'CostRules',
  components: { CloudinaryFetchImg, CardText, AnatomyRow, ImageWithAnatomy },
};
</script>

<style scoped lang="scss">
.card {
  text-align: center;
}

.arrow-pointing-down {
  background: url(https://lycee-tcg.com/rule/images/index_4_img_aicon3.jpg) no-repeat center;
  width: 700px;
  height: 100px;
}

.discard1-box,
.discard2-box {
  background-repeat: no-repeat;
  padding: 15px 0 0 0;
  width: 677px;
  height: 409px;
  font-size: 20px;
  color: #ffffff;
  text-align: center;
}

.discard2-box {
  background-image: url(https://lycee-tcg.com/rule/images/index_4_img4.jpg);
}

.discard1-box {
  background-image: url(https://lycee-tcg.com/rule/images/index_4_img5.jpg);
}

.type-of-costs-table {
  border-collapse: collapse;
}

.type-of-costs-table {
  td {
    border: 1px solid gray;
    padding: 0.5em;
  }

  td:first-child {
    text-align: center;
  }
}
</style>
