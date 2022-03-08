<template>
  <DashboardWidget
    id="intranetagglo_panel"
    :items="items"
    :loading="loading"
    empty-content-icon="icon-app"
    :empty-content-message="'Pas d\'actualités'"
  />
</template>

<script>
import { DashboardWidget } from '@nextcloud/vue-dashboard'
import { loadState } from '@nextcloud/initial-state'
import { generateUrl, imagePath } from '@nextcloud/router'
// import moment from '@nextcloud/moment'
export default {
  name: 'Dashboard',
  components: {
    DashboardWidget,
  },
  data() {
    return {
      news: [],
      loading: true,
    }
  },
  computed: {
    items() {
      return this.news.map((item) => {
        return {
          mainText: item.title,
          avatarUrl: imagePath('intranetagglo', 'LogoCA2BM.png'),
          avatarUsername: item.author,
          targetUrl: generateUrl('/apps/intranetagglo/') + item.id,
          overlayIconUrl: imagePath('intranetagglo', 'empty.svg'),
          subText: `${item.author}` //${moment(item.time, 'X').fromNow()}
        }
      })
    }
  },
  mounted() {
    try {
      this.news = loadState('intranetagglo', 'intranetagglo_dashboard')
      this.loading = false
    } catch (e) {
      console.error(e)
    }
  },
}
</script>