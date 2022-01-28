<template>
  <div id="news-frame">
    <div id="news-container" class="news-container">
      <div v-bind:class="IsAdmin() ? 'news-header admin-view' : 'news-header'">
        <h2 class="news-header-title">Actualités</h2>
        <input type="text" class="searchbar" v-model="search" placeholder="Rechercher.." />
        <NewsAdd v-if="IsAdmin()" />
      </div>
      <div id="news-row" class="news-row">
        <NewsMedium id="news1" v-bind:news="appsarray[0]" />
        <NewsMedium id="news2" v-bind:news="appsarray[1]" />
        <NewsMedium id="news3" v-bind:news="appsarray[2]" />
        <b-icon class="news-return" icon="arrow-return-left" @click="closeNews()"></b-icon>
      </div>
      <b-pagination class="news-pagination" v-model="currentPage" pills :total-rows="rows"></b-pagination>
    </div>
    <Apps class="Apps" />
  </div>
</template>

<script>
import NewsMedium from './news/news.comp'
import Apps from './Apps'
import NewsAdd from './news/news-add.bdd'
export default {
  name: 'News',
  props: {
    msg: String
  },
  components: {
    NewsMedium,
    Apps,
    NewsAdd
  },
  data: function () {
    return {
      actually: "",
      categorysearch: '',
      maxvisiblenews: 6,
      addNews: false,
      AddNewsModal: false,
      userid: {
        name: 'Clément',
        groups: ['admin', 'info']
      },
      appsarray: {
        0: {
          'id': 1,
          'title': 'DES CHOCOLATS POUR NOEL',
          'description': 'En fait y aura pas de chocolat c\'est le covid les enfants',
          'text': '<p class="article_content"></p><p>Chers tous,</p><p>L’année qui vient de se terminer aura évidemment été marquée par l’épidémie du Covid. Aussi mes pensées se tournent d’abord vers ceux qui ont été touchés par la perte de personnes chères ou par la maladie.</p><p>Nous sommes tous dans l’attente d’un retour à la normale, dans l’inquiétude pour nos proches et nous-mêmes, dans le doute quant aux restrictions en place ou potentielles. Tout ceci nous perturbe au quotidien et nous empêche parfois de penser sereinement à l’avenir. Pour autant nous sommes des agents territoriaux au service du public et c’est la raison pour laquelle nous avons le devoir, autant que faire se peut, d’ assumer la continuité du service public. Je compte une fois encore sur l’implication de tous, sur la force du collectif.</p><p>L’année 2022 sera consacrée à la solidification des projets en cours avec <strong>un développement et une maîtrise financière renforcés</strong>, de nos compétences et activités, notamment &nbsp;pour gagner en performance. Elle nous permettra également d’intégrer les ambitions de développement avec le projet de territoire en cours d’écriture et les différentes politiques contractuelles ( Contrat global de l’eau-PCAET-Pradet …),l’anticipation relative à la mise en œuvre des nouvelles consignes de tri des déchets ….Pour ce faire ,et compte tenu de quelques mouvements de personnel &nbsp;(mutation, départs en retraite…), l’organisation des services aura encore à connaître des adaptations. Je vous remercie par avance de votre collaboration pour la mise en œuvre de ces ajustements indispensables à la bonne gestion et à «&nbsp;la bonne santé&nbsp;» de notre intercommunalité.</p><p>J’ai la conviction que nous avons maintenant notre avenir entre nos mains, que les procédures inhérentes à l’agrégation de tous les établissements et budgets préexistants touchent enfin à leur terme . Il est de notre responsabilité de nous préparer aux nouveaux enjeux avec confiance.</p><p>Que 2022 nous apporte la sérénité dans nos projets et de la continuité dans nos succès.</p><p>Je vous souhaite une très bonne année, faite de belles rencontres et beaux moments. Qu’elle vous apporte, ainsi qu’à ceux qui vous sont chers, la santé et le bien être dans vos vies personnelle et professionnelle.</p><p>Prenez-soin de vous.</p><p><strong>Didier BÉE</strong></p>',
          'photos': './img/photo-h-1.jpg',// require("../assets/photo-h-1.jpg"),
          'category': 'Note de service',
          'authgroup': 'info',
          'visible': true
        },
        1: {
          'id': 2,
          'title': 'VOEUX DU DGS',
          'description': '',
          'text': '<p class="article_content"></p><p>Chers tous,</p><p>L’année qui vient de se terminer aura évidemment été marquée par l’épidémie du Covid. Aussi mes pensées se tournent d’abord vers ceux qui ont été touchés par la perte de personnes chères ou par la maladie.</p><p>Nous sommes tous dans l’attente d’un retour à la normale, dans l’inquiétude pour nos proches et nous-mêmes, dans le doute quant aux restrictions en place ou potentielles. Tout ceci nous perturbe au quotidien et nous empêche parfois de penser sereinement à l’avenir. Pour autant nous sommes des agents territoriaux au service du public et c’est la raison pour laquelle nous avons le devoir, autant que faire se peut, d’ assumer la continuité du service public. Je compte une fois encore sur l’implication de tous, sur la force du collectif.</p><p>L’année 2022 sera consacrée à la solidification des projets en cours avec <strong>un développement et une maîtrise financière renforcés</strong>, de nos compétences et activités, notamment &nbsp;pour gagner en performance. Elle nous permettra également d’intégrer les ambitions de développement avec le projet de territoire en cours d’écriture et les différentes politiques contractuelles ( Contrat global de l’eau-PCAET-Pradet …),l’anticipation relative à la mise en œuvre des nouvelles consignes de tri des déchets ….Pour ce faire ,et compte tenu de quelques mouvements de personnel &nbsp;(mutation, départs en retraite…), l’organisation des services aura encore à connaître des adaptations. Je vous remercie par avance de votre collaboration pour la mise en œuvre de ces ajustements indispensables à la bonne gestion et à «&nbsp;la bonne santé&nbsp;» de notre intercommunalité.</p><p>J’ai la conviction que nous avons maintenant notre avenir entre nos mains, que les procédures inhérentes à l’agrégation de tous les établissements et budgets préexistants touchent enfin à leur terme . Il est de notre responsabilité de nous préparer aux nouveaux enjeux avec confiance.</p><p>Que 2022 nous apporte la sérénité dans nos projets et de la continuité dans nos succès.</p><p>Je vous souhaite une très bonne année, faite de belles rencontres et beaux moments. Qu’elle vous apporte, ainsi qu’à ceux qui vous sont chers, la santé et le bien être dans vos vies personnelle et professionnelle.</p><p>Prenez-soin de vous.</p><p><strong>Didier BÉE</strong></p>',
          'photos': '',
          'category': 'Note de service',
          'authgroup': 'info',
          'visible': true
        },
        2: {
          'id': 3,
          'title': 'ORGANIGRAMME - SEPTEMBRE 2021',
          'description': 'http://10.200.1.5/annuaire/',
          'text': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam in enim vitae tellus dapibus fringilla sed ut orci. Cras placerat, justo ac vehicula vehicula, odio nulla aliquet ligula.',
          'photos': './img/photo5.jpg', //require("../assets/photo5.jpg"),
          'category': 'Note de service',
          'authgroup': 'info',
          'visible': true
        },
      }
    }
  },
  computed: {
    categoryoptions() {
      var News = this.$store.state.News
      var CategoryArray = []
      for (var i = 0; i < News.length; i++) {
        CategoryArray.push(News[i].category)
      }
      const uniqueCaterogy = Array.from(new Set(CategoryArray))
      return uniqueCaterogy
    },
    search: {
      get() {
        return this.$store.state.search
      },
      set(value) {
        this.$store.commit('updateSearch', value)
      }
    },
    droptextc() {
      if (this.droptext == '') {
        return 'Filtre'
      }
      return this.droptext;
    },
    categoryfilter() {
      return this.$store.state.categoryfilter
    },
    filteredNews() {
      function groupcheck(neededgroups, usergroups) {
        var missinggroup = 0
        for (var i = 0; i < neededgroups.length; i++) if (!usergroups.includes(neededgroups[i])) missinggroup++;
        return missinggroup
      }
      function Searchedcheck(news, search) {
        if (news.title.toLowerCase().includes(search.toLowerCase())) return true
        else return false
      }
      function Categorycheck(category, categoryfilter) {
        if (categoryfilter == category || categoryfilter == '') return true
        else return false
      }

      function filter(news, search, category, categoryfilter) {
        if (Searchedcheck(news, search) && Categorycheck(category, categoryfilter)) return true
        else return false
      }

      var categoryfilter = this.$store.state.categoryfilter
      var News = this.$store.state.News


      return News.filter(newsfilter => {
        if (!groupcheck(newsfilter.authgroup, this.$store.state.user.groups)) {
          return filter(newsfilter, this.search, newsfilter.category, categoryfilter)
        }
        else return false
      }).slice(0, this.maxvisiblenews)
    }
  },
  methods: {
    handleScroll() {
      const els = document.querySelectorAll('.news')
      setInterval(function () {
      }, 1000)
      els.forEach((el) => {
        const elTop = el.getBoundingClientRect().top
        const elBottom = el.getBoundingClientRect().bottom
        if (elTop >= 0 || elBottom <= 0) {
          this.isActive = false
          el.classList.remove('active')
        } if (elTop <= 300 && elBottom >= 0) {
          this.isActive = true
          el.classList.add('active')
        }
      })
    },
    IsAdmin() {
      return this.userid.groups.includes('admin')
    },
    closeNews() {
      let newsrow = document.getElementById('news-row')
      newsrow.classList.toggle(this.actually)
    }
  },
  mounted() {
    var news = document.getElementsByClassName('news');
    let newsrow = document.getElementById('news-row')
    news[0].addEventListener('click', () => {
      if (!newsrow.classList.contains('left')) {
        newsrow.classList.toggle('left')
        this.actually = "left"
      }

    })
    news[1].addEventListener('click', () => {
      if (!newsrow.classList.contains('center')) {
        newsrow.classList.toggle('center')
        this.actually = "center"
      }
    })
    news[2].addEventListener('click', () => {
      if (!newsrow.classList.contains('right')) {
        newsrow.classList.toggle('right')
        this.actually = "right"
      }
    })
  },
  destroyed() {
    var target = document.getElementById('news-container');
    target.removeEventListener('scroll', this.handleScroll);
  }
}
</script>