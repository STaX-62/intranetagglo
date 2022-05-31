<template>
  <div class="sidenav">
    <div class="sidenav-logo">
      <img v-bind:src="image" />
    </div>
    <div class="sidenav-menu">
      <div class="section-block" v-for="(section,index) in MenuToDisplay" :key="'B'+index">
        <button class="section">
          <div class="section-icon">
            <b-icon v-bind:icon="section.icon"></b-icon>
          </div>
          <div class="section-title">{{ section.title }}</div>
        </button>
        <div
          v-bind:id="'menu-'+index+'-'+subindex"
          class="menu"
          v-for="(menu,subindex) in MenuToDisplay[index].childs"
          :key="'B'+subindex"
          @click="ExtendSubMenu(index,subindex)"
        >
          <div
            class="submenu-title"
            @click="OpenLink(menu.link, isEmpty(MenuToDisplay[index].childs[subindex].childs))"
          >
            <div class="caret" v-if="!isEmpty(MenuToDisplay[index].childs[subindex].childs)">▷</div>
            {{ menu.title }}
          </div>
          <div v-bind:id="'container-'+ index + '-'+ subindex" class="menu-container">
            <a
              class="nav-link text-truncate"
              v-for="(submenu,subsubindex) in MenuToDisplay[index].childs[subindex].childs"
              :key="'B'+subsubindex"
              v-bind:href="submenu.link"
            >
              <span class="d-sm-inline">{{ submenu.title }}</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="Raccourcis">
      <menu-admin-overview v-if="isAdmin" />
      <a class="Files" href="https://cloud.ca2bm.fr/index.php/f/1183804">
        <b-icon class="doc-icon" icon="folder"></b-icon>
        <div>Documents</div>
      </a>
    </div>
  </div>
</template>
<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import menuAdminOverview from './menu/menuAdminOverview.vue'
export default {
  name: 'Navigation',
  components: {
    menuAdminOverview
  },
  data: function () {
    return {
      image: '/nextcloud/apps/intranetagglo/img/LogoCA2BM.png',
      user: [],
      lastOpenedContainer: null,
      menuInBDD: [[], [], []]
    }
  },
  watch: {
    updating: function (val) {
      if (val) {
        axios.get(generateUrl(`apps/intranetagglo${'/menusG'}`))
          .then((response) => {
            this.menuInBDD = response.data
            this.$store.commit('setMenuUpdating', false)
          })
      }
    },
  },
  computed: {
    updating() {
      return this.$store.state.menuupdating
    },
    isAdmin() {
      return this.$store.state.isAdmin
    },
    MenuToDisplay() {
      var sectionArray = this.menuInBDD[0]
      sectionArray.forEach((section) => {
        var menuArray = this.menuInBDD[1].filter(menu => menu.sectionid == section.sectionid);
        menuArray.forEach((menu) => {
          menu.childs = this.menuInBDD[2].filter(submenu => submenu.menuid == menu.menuid && submenu.sectionid == menu.sectionid);
        })
        section.childs = menuArray;
      })
      return sectionArray
    },
  },
  methods: {
    isEmpty(array) {
      if (array.length > 0) {
        return false
      }
      else return true
    },
    OpenLink(link, isEmpty) {
      if (link != '' && isEmpty) {
        window.open(link, '_blank');
      }
    },
    CategorySet(value) {
      this.droptext = value
      this.$store.commit('updateCategories', value)
    },
    ExtendSubMenu(index, subindex) {
      if (this.lastOpenedContainer != null && this.lastOpenedContainer != index + '-' + subindex) {
        document.getElementById("menu-" + this.lastOpenedContainer).firstChild.classList.toggle("active")
        document.getElementById("container-" + this.lastOpenedContainer).classList.toggle('active');
      }
      document.getElementById("menu-" + index + '-' + subindex).firstChild.classList.toggle("active")
      document.getElementById("container-" + index + '-' + subindex).classList.toggle('active');
      if (this.lastOpenedContainer == index + '-' + subindex) this.lastOpenedContainer = null;
      else this.lastOpenedContainer = index + '-' + subindex;
    },

  },
  mounted() {
    axios.get(generateUrl(`apps/intranetagglo${'/menusG'}`))
      .then((response) => {
        this.menuInBDD = response.data
      })
  }
}
</script>

<style scoped>
.sidenav {
  position: relative;
  height: 100%;
  width: 250px;
  z-index: 1;
  background: var(--color-mode-3);
  border-right: 2px solid var(--color-mode-4) !important;
  box-shadow: 2px 2px 3px rgb(55 84 170 / 15%), 2px 2px 3px rgb(55 84 170 / 15%),
    7px 7px 15px rgb(55 84 170 / 15%), -7px -7px 20px rgb(0 0 0 / 10%);
  display: grid;
  grid-template-columns: 100%;
  grid-auto-rows: 130px auto max-content;
  grid-template-areas:
    "Logo"
    "Menu"
    "Buttons";
}
#App.dark .sidenav {
  box-shadow: 0 -5px 5px hsl(0deg 0% 100% / 5%);
}

/* Some media queries for responsiveness */
@media screen and (max-height: 450px) {
  .sidenav {
    padding-top: 15px;
  }
}

.sidenav-logo {
  width: 200px;
  margin: auto;
  margin-top: 10px;
  margin-bottom: 10px;
  grid-area: Logo;
}

.sidenav-logo img {
  width: 200px;
  filter: drop-shadow(0px 0px 20px var(--color-mode-contrast-2));
}

.sidenav-menu {
  overflow-x: hidden;
  overflow-y: auto;
  max-height: 620px;
  grid-area: Menu;
}

.sidenav-menu::-webkit-scrollbar {
  display: none;
}
.section:nth-of-type(even) {
  top: 0;
  position: sticky;
  background-color: #9fc737 !important;
}

.section:nth-of-type(odd) {
  top: 0;
  position: sticky;
  background-color: #0eb4ed !important;
}

.section {
  z-index: 100;
  padding: 0 8px 0 0;
  text-decoration: none;
  font-size: 20px;
  display: flex;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  outline: none;
  transition: 0.5s;
  border-radius: 0px;
  box-shadow: rgb(0 178 255 / 20%) 1px 1px 0px 1px;
  font-family: PetitaBold;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  margin-bottom: 10px;
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
}

.section-block {
  padding-bottom: 10px;
  padding-right: 5px;
}

.section-icon {
  display: flex;
  justify-content: center;
  align-items: center;
  color: var(--color-mode-1);
  width: 42px;
  height: 42px;
  border-width: 0px;
  border-style: solid;
  background-color: var(--color-mode-contrast-2);
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
}

.menu-title,
.section-title {
  color: var(--color-mode-2);
  padding-left: 10px;
  width: 200px;
  margin-top: auto;
  margin-bottom: auto;
  -moz-user-select: none; /* Firefox */
  -webkit-user-select: none; /* Chrome, Safari, Opéra depuis la version 15 */
  -ms-user-select: none; /* Internet explorer depuis la version 10 et Edge */
  user-select: none; /* Propriété standard */
}

.section-title{
  font-weight: 500;
}

.menu {
  padding: 2px 0 2px 0;
  text-decoration: none;
  font-size: 15px;
  display: block;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  outline: none;
  transition: 0.5s;
  border-radius: 100px;
}

.menu:hover {
  color: var(--color-mode-contrast-1);
}

.submenu-title {
  color: var(--color-mode-contrast-4);
  padding-left: 12px;
  display: flex;
  transition: 0.5s !important;
  letter-spacing: 0.01rem;
  font-family: PetitaBold;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  -moz-user-select: none; /* Firefox */
  -webkit-user-select: none; /* Chrome, Safari, Opéra depuis la version 15 */
  -ms-user-select: none; /* Internet explorer depuis la version 10 et Edge */
  user-select: none; /* Propriété standard */
}

.submenu-title:hover {
  color: var(--color-mode-contrast-1);
}

.submenu-title.active .caret {
  transform: rotate(90deg);
}

.submenu-title.active {
  color: var(--color-mode-contrast-1);
  transform: translateX(10px);
  overflow: hidden;
  text-overflow: fade(10px);
  white-space: nowrap;
}

.caret {
  margin-right: 5px;
  transition: transform 0.5s;
}

.menu-container {
  display: none;
  background-color: var(--color-mode-1);
  padding-left: 8px;
}

.menu-container.active {
  display: block;
}

.nav-link {
  padding: 3px 8px 3px 16px;
  text-decoration: none;
  color: var(--color-mode-contrast-4);
  transition: 0.5s;
  font-weight: 400;
  width: 100%;
  text-align: left;
  cursor: pointer;
  outline: none;
  display: flex;
  border: none;
  letter-spacing: 0.02rem;
  font-family: PetitaMedium;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.Raccourcis {
  position: relative;
  width: 100%;
  height: max-content;
  display: grid;
  grid-template-columns: 200px;
  grid-auto-rows: 40px;
  grid-auto-flow: dense;
  grid-template-areas: ".";
  grid-gap: 10px 15px;
  justify-content: center;
  vertical-align: middle;
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: 13px;
  grid-area: Buttons;
}

.Files {
  display: flex;
  align-items: center;
  justify-content: center;
  border: solid 2px;
  border-radius: 0.25rem;
  padding: 0.375rem 0.75rem;
  text-align: center;
  color: var(--color-mode-1);
  background-color: var(--color-primary-intra);
  border-color: var(--color-primary-intra);
  font-size: 15px;
  box-shadow: var(--color-mode-shadow-1) 0px 3px 1px -2px,
    var(--color-mode-shadow-2) 0px 2px 2px 0px,
    var(--color-mode-shadow-3) 0px 1px 5px 0px;
  grid-row: auto / span 2;
  font-weight: 700;
  display: block;
}
</style>
