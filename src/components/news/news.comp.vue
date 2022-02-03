<template>
  <div class="news news-customcolor" v-bind:style="'--news-color: ' + newscolor">
    <div class="news-textbox">
      <div v-bind:id="'news-textbox-block' + news.id" class="news-textbox-block">
        <div class="news-title">{{ news.title }}</div>
        <div class="news-subtitle" :class="{'active': isActive}">{{ news.description }}</div>
        <div class="news-bar"></div>
        <div class="news-description" v-html="news.text"></div>
      </div>
      <div class="news-img-container">
        <img class="news-img" v-bind:src="news.photos" v-if="news.photos" />
      </div>
      <div class="news-tagbox">
        <span class="news-tag">{{ news.category }}</span>
        <NewsVisibility v-bind:visibility="news.visibility" />
        <NewsUpdate />
        <NewsDelete />
      </div>
    </div>
  </div>
</template>

<script>
import NewsVisibility from './news-visibility.bdd'
import NewsUpdate from './news-update.bdd'
import NewsDelete from './news-del.bdd'

export default {
  name: 'NewsMedium',
  components: {
    NewsUpdate,
    NewsVisibility,
    NewsDelete
  },
  props: {
    news: Object,
  },
  computed: {
  },
  methods: {
  },
  mounted() {
    console.log(this.news)
    let textboxblock = document.getElementById('news-textbox-block' + this.news.id)
    if (this.news.photos == "") textboxblock.setAttribute('noimg', 'true')
    else textboxblock.setAttribute('noimg', 'false')
  },
  data: function () {
    return {
      newscolor: '#00B2FF'
    }
  }
}
</script>

<style scoped>
.news {
  position: relative;
  width: 30%;
  max-height: 100%;
  border-radius: 10px;
  background-color: #fff;
  border: 2px solid var(--color-mode-4);
  font-size: 18px;
  overflow: hidden;
  cursor: pointer;
  box-shadow: 0 4px 21px -12px var(--color-mode-shadow-4);
  transition: box-shadow 0.2s ease, transform 0.2s ease, height 1s ease;
}
.news-row.right .news,
.news-row.left .news,
.news-row.center .news {
  cursor: default;
}

.news-textbox-block[noimg="false"] {
  flex: 0 0 calc(30% - 20px);
  height: 100%;
  display: grid;
  grid-template-columns: 100%;
  grid-template-rows: max-content max-content max-content auto;
  grid-template-areas:
    "."
    "."
    "."
    ".";
}
.news-textbox-block[noimg="true"] {
  flex: 0 0 100%;
  height: 100%;
}
.news-row.right .news-description,
.news-row.left .news-description,
.news-row.center .news-description {
  overflow: auto !important;
}
.news-row.right .news-textbox-block,
.news-row.left .news-textbox-block,
.news-row.center .news-textbox-block {
  padding: 12px 12px 40px 12px;
}

.news-row:not(.left):not(.right):not(.center) .news-textbox-block:after {
  content: "";
  position: absolute;
  z-index: 1;
  bottom: 0;
  left: 0;
  pointer-events: none;
  background-image: linear-gradient(
    to bottom,
    rgba(255, 255, 255, 0),
    rgba(255, 255, 255, 1) 90%
  );
  width: 100%;
  height: 4em;
}
.news-row.right .news:hover,
.news-row.left .news:hover,
.news-row.center .news:hover,
.news-row.left .news:hover .news-img,
.news-row.center .news:hover .news-img,
.news-row.right .news:hover .news-img {
  transform: none !important;
}
.news-row.right .news-bar,
.news-row.left .news-bar,
.news-row.center .news-bar {
  width: 128px;
  transition: none;
}
.news-row.right:hover .news-bar,
.news-row.left:hover .news-bar,
.news-row.center:hover .news-bar {
  width: 128px;
}

.news:hover {
  box-shadow: 0 34px 32px -33px rgba(0, 0, 0, 0.18);
  transform: translate(0px, -3px);
}
.news-img {
  position: sticky !important;
  max-height: 100%;
  max-width: 100%;
  transition: transform 0.2s ease;
  overflow: hidden;
  padding-right: 12px;
  z-index: 2;
}
.news:hover .news-img {
  transform: scale(1.05) rotate(1deg);
}
.news:hover .news-bar {
  width: 40%;
}
.news-row:not(.left):not(.right):not(.center) .news-textbox {
  position: relative;
  padding: 12px 12px 40px 12px;
  width: 100%;
  height: 100%;
  font-size: 17px;
}
.news-row.right .news-textbox,
.news-row.left .news-textbox,
.news-row.center .news-textbox {
  width: auto;
  display: flex;
  width: 100%;
  height: 100%;
}

.news-row:not(.left):not(.right):not(.center) .news-textbox {
  height: 100%;
  display: grid;
  grid-template-columns: 100%;
  grid-template-rows: minmax(0, 100%) max-content;
  overflow: hidden;
}
.news-row.right .news-img-container,
.news-row.left .news-img-container,
.news-row.center .news-img-container {
  grid-area: Img;
  margin: auto;
}

.news-textbox * {
  position: relative;
}
.news-title {
  font-family: "Voces", "Open Sans", arial, sans-serif;
  font-size: 24px;
}
.news-subtitle {
  font-family: "Voces", "Open Sans", arial, sans-serif;
  color: #888;
}
.news-bar {
  left: -2px;
  width: 20%;
  height: 5px;
  margin-top: 5px;
  margin-bottom: 5px;
  border-radius: 5px;
  background-color: #424242;
  transition: width 0.2s ease;
}
.news-blue .news-bar {
  background-color: #0088ff;
}
.news-blue::before {
  background-image: linear-gradient(-70deg, #0088ff, transparent 50%);
}
.news-red .news-bar {
  background-color: #d62f1f;
}
.news-red::before {
  background-image: linear-gradient(-70deg, #d62f1f, transparent 50%);
}
.news-green .news-bar {
  background-color: #40bd00;
}
.news-green::before {
  background-image: linear-gradient(-70deg, #40bd00, transparent 50%);
}
.news-yellow .news-bar {
  background-color: #f5af41;
}
.news-yellow::before {
  background-image: linear-gradient(-70deg, #f5af41, transparent 50%);
}
.news-orange .news-bar {
  background-color: #ff5722;
}
.news-orange::before {
  background-image: linear-gradient(-70deg, #ff5722, transparent 50%);
}
.news-brown .news-bar {
  background-color: #c49863;
}
.news-brown::before {
  background-image: linear-gradient(-70deg, #c49863, transparent 50%);
}
.news-grey .news-bar {
  background-color: #424242;
}
.news-grey::before {
  background-image: linear-gradient(-70deg, #424242, transparent 50%);
}
.news-customcolor .news-bar {
  background-color: var(--news-color);
}
.news-customcolor::before {
  background-image: linear-gradient(-70deg, var(--news-color), transparent 50%);
}

.news-textbox-block[noimg="true"] .news-description {
  overflow: hidden;
  font-size: 15px;
  height: calc(100% - 60px);
  margin: 5px;
  color: #424242;
  text-overflow: ellipsis;
}
.news-textbox-block[noimg="false"] .news-description {
  overflow: hidden;
  font-size: 15px;
  height: 100%;
  margin: 5px;
  color: #424242;
  text-overflow: ellipsis;
}
.news-tagbox {
  position: absolute;
  border-radius: 10px;
  padding: 0 20px;
  display: flex;
  align-items: center;
  width: calc(100% - 40px);
  bottom: 0;
  font-size: 14px;
  cursor: default;
  user-select: none;
  background: #fff;
}
.news-tag {
  display: inline-block;
  height: 1.7rem;
  font-size: 13px;
  background: #e0e0e0;
  color: #777;
  border-radius: 3px 0 0 3px;
  line-height: 26px;
  padding: 0 10px 0 23px;
  position: relative;
  margin-right: 20px;
  cursor: default;
  user-select: none;
  transition: color 0.2s;
}
.news-tag::before {
  content: "";
  position: absolute;
  background: #fff;
  border-radius: 10px;
  box-shadow: inset 0 1px rgba(0, 0, 0, 0.25);
  height: 6px;
  left: 10px;
  width: 6px;
  top: 10px;
}
.news-tag::after {
  content: "";
  position: absolute;
  border-bottom: 13px solid transparent;
  border-left: 10px solid #e0e0e0;
  border-top: 13px solid transparent;
  right: -10px;
  top: 0;
}
</style>