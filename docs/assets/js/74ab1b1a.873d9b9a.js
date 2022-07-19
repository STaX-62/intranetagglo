"use strict";(self.webpackChunkintranetdoc=self.webpackChunkintranetdoc||[]).push([[315],{3905:(e,t,n)=>{n.d(t,{Zo:()=>p,kt:()=>m});var r=n(7294);function i(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function a(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function o(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?a(Object(n),!0).forEach((function(t){i(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):a(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function l(e,t){if(null==e)return{};var n,r,i=function(e,t){if(null==e)return{};var n,r,i={},a=Object.keys(e);for(r=0;r<a.length;r++)n=a[r],t.indexOf(n)>=0||(i[n]=e[n]);return i}(e,t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);for(r=0;r<a.length;r++)n=a[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(i[n]=e[n])}return i}var u=r.createContext({}),s=function(e){var t=r.useContext(u),n=t;return e&&(n="function"==typeof e?e(t):o(o({},t),e)),n},p=function(e){var t=s(e.components);return r.createElement(u.Provider,{value:t},e.children)},c={inlineCode:"code",wrapper:function(e){var t=e.children;return r.createElement(r.Fragment,{},t)}},d=r.forwardRef((function(e,t){var n=e.components,i=e.mdxType,a=e.originalType,u=e.parentName,p=l(e,["components","mdxType","originalType","parentName"]),d=s(n),m=i,g=d["".concat(u,".").concat(m)]||d[m]||c[m]||a;return n?r.createElement(g,o(o({ref:t},p),{},{components:n})):r.createElement(g,o({ref:t},p))}));function m(e,t){var n=arguments,i=t&&t.mdxType;if("string"==typeof e||i){var a=n.length,o=new Array(a);o[0]=d;var l={};for(var u in t)hasOwnProperty.call(t,u)&&(l[u]=t[u]);l.originalType=e,l.mdxType="string"==typeof e?e:i,o[1]=l;for(var s=2;s<a;s++)o[s]=n[s];return r.createElement.apply(null,o)}return r.createElement.apply(null,n)}d.displayName="MDXCreateElement"},3074:(e,t,n)=>{n.r(t),n.d(t,{assets:()=>u,contentTitle:()=>o,default:()=>c,frontMatter:()=>a,metadata:()=>l,toc:()=>s});var r=n(7462),i=(n(7294),n(3905));const a={sidebar_position:3},o="Alertes",l={unversionedId:"administration/alerts",id:"administration/alerts",title:"Alertes",description:"---",source:"@site/docs/administration/alerts.mdx",sourceDirName:"administration",slug:"/administration/alerts",permalink:"/intranetagglo/docs/administration/alerts",draft:!1,tags:[],version:"current",sidebarPosition:3,frontMatter:{sidebar_position:3},sidebar:"tutorialSidebar",previous:{title:"Navigation",permalink:"/intranetagglo/docs/administration/navigation"},next:{title:"Actualit\xe9s",permalink:"/intranetagglo/docs/administration/news"}},u={},s=[{value:"Cr\xe9er une alerte",id:"cr\xe9er-une-alerte",level:2},{value:"Modifier une alerte",id:"modifier-une-alerte",level:2},{value:"Suppression d\u2019une alerte",id:"suppression-dune-alerte",level:2}],p={toc:s};function c(e){let{components:t,...a}=e;return(0,i.kt)("wrapper",(0,r.Z)({},p,a,{components:t,mdxType:"MDXLayout"}),(0,i.kt)("h1",{id:"alertes"},"Alertes"),(0,i.kt)("hr",null),(0,i.kt)("p",null,"Les alertes sont enregistr\xe9es comme des Actualit\xe9s mais poss\xe9dant une date d\u2019expiration."),(0,i.kt)("p",null,(0,i.kt)("img",{alt:"Capture du menu d&#39;ajout d&#39;alerte",src:n(802).Z,width:"698",height:"358"})),(0,i.kt)("h2",{id:"cr\xe9er-une-alerte"},"Cr\xe9er une alerte"),(0,i.kt)("p",null,"Il faut ",(0,i.kt)("strong",{parentName:"p"},"appuyer sur le bouton ",(0,i.kt)("inlineCode",{parentName:"strong"},"+"))," du titre de la section, ce qui ouvre le menu ci-dessus."),(0,i.kt)("blockquote",null,(0,i.kt)("p",{parentName:"blockquote"},(0,i.kt)("strong",{parentName:"p"},"Attention !")," ce bouton n\u2019est pas pr\xe9sent sur l\u2019affichage mobile.")),(0,i.kt)("p",null,"Un alerte se compose de :"),(0,i.kt)("ul",null,(0,i.kt)("li",{parentName:"ul"},"Un ",(0,i.kt)("inlineCode",{parentName:"li"},"titre")),(0,i.kt)("li",{parentName:"ul"},"Une restriction de ",(0,i.kt)("inlineCode",{parentName:"li"},"groupe d\u2019utilisateurs")," ",(0,i.kt)("a",{parentName:"li",href:"groups"},"(voir fonctionnement des groupes)")),(0,i.kt)("li",{parentName:"ul"},"La",(0,i.kt)("inlineCode",{parentName:"li"}," date d\u2019expiration")," de l\u2019actualit\xe9 (obligatoire)"),(0,i.kt)("li",{parentName:"ul"},"Le ",(0,i.kt)("inlineCode",{parentName:"li"},"contenu de l\u2019alerte")," en markdown")),(0,i.kt)("h2",{id:"modifier-une-alerte"},"Modifier une alerte"),(0,i.kt)("p",null,"Le bouton de modification ",(0,i.kt)("img",{alt:"icone de modification",src:n(6308).Z,width:"16",height:"16"})," ",(0,i.kt)("em",{parentName:"p"},"(\xe0 droite du d\xe9compte de jours restant avant expiration)")," permet de modifier l'alerte."),(0,i.kt)("blockquote",null,(0,i.kt)("p",{parentName:"blockquote"},"Lors d\u2019une modification d\u2019une alerte, penser \xe0 bien repr\xe9ciser la date d\u2019expiration.")),(0,i.kt)("h2",{id:"suppression-dune-alerte"},"Suppression d\u2019une alerte"),(0,i.kt)("p",null,"Pour supprimer une alerte, il faut ouvrir le menu de modification.\nle bouton de suppression se situe en bas de ce menu."))}c.isMDXComponent=!0},802:(e,t,n)=>{n.d(t,{Z:()=>r});const r=n.p+"assets/images/Image2-3054227c5cdaaea04f5f613e7fefefe4.png"},6308:(e,t,n)=>{n.d(t,{Z:()=>r});const r="data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTYgMTYiIHdpZHRoPSIxZW0iIGhlaWdodD0iMWVtIiBmb2N1c2FibGU9ImZhbHNlIiByb2xlPSJpbWciIGFyaWEtbGFiZWw9InBlbmNpbCBzcXVhcmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmktcGVuY2lsLXNxdWFyZSBteC1hdXRvIGItaWNvbiBiaSIgZGF0YS12LTQxYmU2NjMzPSIiPg0KICAgIDxnPg0KICAgICAgICA8cGF0aCBkPSJNMTUuNTAyIDEuOTRhLjUuNSAwIDAgMSAwIC43MDZMMTQuNDU5IDMuNjlsLTItMkwxMy41MDIuNjQ2YS41LjUgMCAwIDEgLjcwNyAwbDEuMjkzIDEuMjkzem0tMS43NSAyLjQ1Ni0yLTJMNC45MzkgOS4yMWEuNS41IDAgMCAwLS4xMjEuMTk2bC0uODA1IDIuNDE0YS4yNS4yNSAwIDAgMCAuMzE2LjMxNmwyLjQxNC0uODA1YS41LjUgMCAwIDAgLjE5Ni0uMTJsNi44MTMtNi44MTR6Ij48L3BhdGg+DQogICAgICAgIDxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTEgMTMuNUExLjUgMS41IDAgMCAwIDIuNSAxNWgxMWExLjUgMS41IDAgMCAwIDEuNS0xLjV2LTZhLjUuNSAwIDAgMC0xIDB2NmEuNS41IDAgMCAxLS41LjVoLTExYS41LjUgMCAwIDEtLjUtLjV2LTExYS41LjUgMCAwIDEgLjUtLjVIOWEuNS41IDAgMCAwIDAtMUgyLjVBMS41IDEuNSAwIDAgMCAxIDIuNXYxMXoiPjwvcGF0aD4NCiAgICA8L2c+DQo8L3N2Zz4="}}]);