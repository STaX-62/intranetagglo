<template>
  <div>
    <div class="settings-wrapper">
      <h2 class="settings-title">Paramètres</h2>
      <b-icon id="close" class="settings-close" icon="x-circle-fill"></b-icon>
      <div class="settings-block">
        <h5 class="settings-label">motifs du fond</h5>
        <select id="pattern-select" class="settings-pattern-select">
          <option value="1">modele 1</option>
          <option value="2">modele 2</option>
          <option value="3">modele 3</option>
          <option value="4">modele 4</option>
          <option value="5">modele 5</option>
          <option value="6">modele 6</option>
          <option value="7">modele 7</option>
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


    function Toggle() {
      document.getElementById('Settings').classList.toggle('hidden')
      document.getElementById('apps-container').classList.toggle('hidden')
      this.$emit('hide')
    }

    function Pattern(e) {
      var pattern = document.createAttribute("pattern");
      pattern.value = e.target.value;
      localStorage.setItem('patterns', e.target.value);
      document.getElementById('Home').attributes.setNamedItem(pattern)
    }

    function Variant(e) {
      var variant = document.createAttribute("variant");
      variant.value = e.target.value;
      localStorage.setItem('variant', e.target.value);
      document.getElementById('Home').attributes.setNamedItem(variant)
    }

    document.getElementById('close').addEventListener("click", Toggle);
    document.getElementById('pattern-select').addEventListener("change", Pattern);
    document.getElementById('variant-select').addEventListener("change", Variant);
  },
  created: function () {
    var patterns = localStorage.getItem('patterns');
    var variant = localStorage.getItem('variant');

    if (patterns) {
      this.patterns = patterns;
    }
    if (variant) {
      this.backgroundcolor = variant;
    }
  }
}
</script>