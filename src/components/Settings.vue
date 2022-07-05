<template>
  <div>
    <div class="settings-wrapper">
      <h2 class="settings-title">Paramètres</h2>
      <b-icon id="close" class="settings-close" icon="x-circle-fill"></b-icon>
      <div class="settings-block">
        <h5 class="settings-label">motifs du fond</h5>
        <select id="pattern-select" class="settings-pattern-select">
          <option value="1">axiome</option>
          <option value="2">carrelé</option>
          <option value="3">briques</option>
          <option value="4">carrés</option>
          <option value="5">reliefs</option>
          <option value="6">cubes</option>
          <option value="7">cubes coupés</option>
        </select>
        <h5 class="settings-label">couleur du fond</h5>
        <select id="variant-select" class="settings-pattern-select">
          <option value="1">variante 1</option>
          <option value="2">variante 2</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Settings',
  data: function () {
    return {
      patterns: '2',
      backgroundcolor: '2'
    }
  },
  mounted: function () {

    var patternoptions = document.getElementById('pattern-select')
    var variantoptions = document.getElementById('variant-select')
    patternoptions.children[parseInt(this.patterns) - 1].setAttribute("selected", "selected");
    variantoptions.children[parseInt(this.backgroundcolor) - 1].setAttribute("selected", "selected");

    function Pattern(e) {
      var pattern = document.createAttribute("pattern");
      pattern.value = e.target.value;
      localStorage.setItem('intranetagglo_patterns', e.target.value);
      document.getElementById('App').attributes.setNamedItem(pattern)
    }

    function Variant(e) {
      var variant = document.createAttribute("variant");
      variant.value = e.target.value;
      localStorage.setItem('intranetagglo_variant', e.target.value);
      document.getElementById('App').attributes.setNamedItem(variant)
    }

    document.getElementById('close').addEventListener("click", () => {
      document.getElementById('Settings').classList.toggle('hidden')
      document.getElementById('apps-container').classList.toggle('hidden')
      document.getElementById('settings-box').classList.toggle('hidden')
    });
    document.getElementById('pattern-select').addEventListener("change", Pattern);
    document.getElementById('variant-select').addEventListener("change", Variant);
  },
  created: function () {
    var patterns = localStorage.getItem('intranetagglo_patterns');
    var variant = localStorage.getItem('intranetagglo_variant');

    if (patterns) {
      this.patterns = patterns;
    }
    if (variant) {
      this.backgroundcolor = variant;
    }
  }
}
</script>