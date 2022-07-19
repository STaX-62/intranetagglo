"use strict";(self.webpackChunkintranetdoc=self.webpackChunkintranetdoc||[]).push([[340],{3905:(e,n,t)=>{t.d(n,{Zo:()=>m,kt:()=>c});var i=t(7294);function r(e,n,t){return n in e?Object.defineProperty(e,n,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[n]=t,e}function a(e,n){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);n&&(i=i.filter((function(n){return Object.getOwnPropertyDescriptor(e,n).enumerable}))),t.push.apply(t,i)}return t}function u(e){for(var n=1;n<arguments.length;n++){var t=null!=arguments[n]?arguments[n]:{};n%2?a(Object(t),!0).forEach((function(n){r(e,n,t[n])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):a(Object(t)).forEach((function(n){Object.defineProperty(e,n,Object.getOwnPropertyDescriptor(t,n))}))}return e}function o(e,n){if(null==e)return{};var t,i,r=function(e,n){if(null==e)return{};var t,i,r={},a=Object.keys(e);for(i=0;i<a.length;i++)t=a[i],n.indexOf(t)>=0||(r[t]=e[t]);return r}(e,n);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);for(i=0;i<a.length;i++)t=a[i],n.indexOf(t)>=0||Object.prototype.propertyIsEnumerable.call(e,t)&&(r[t]=e[t])}return r}var l=i.createContext({}),s=function(e){var n=i.useContext(l),t=n;return e&&(t="function"==typeof e?e(n):u(u({},n),e)),t},m=function(e){var n=s(e.components);return i.createElement(l.Provider,{value:n},e.children)},d={inlineCode:"code",wrapper:function(e){var n=e.children;return i.createElement(i.Fragment,{},n)}},p=i.forwardRef((function(e,n){var t=e.components,r=e.mdxType,a=e.originalType,l=e.parentName,m=o(e,["components","mdxType","originalType","parentName"]),p=s(t),c=r,g=p["".concat(l,".").concat(c)]||p[c]||d[c]||a;return t?i.createElement(g,u(u({ref:n},m),{},{components:t})):i.createElement(g,u({ref:n},m))}));function c(e,n){var t=arguments,r=n&&n.mdxType;if("string"==typeof e||r){var a=t.length,u=new Array(a);u[0]=p;var o={};for(var l in n)hasOwnProperty.call(n,l)&&(o[l]=n[l]);o.originalType=e,o.mdxType="string"==typeof e?e:r,u[1]=o;for(var s=2;s<a;s++)u[s]=t[s];return i.createElement.apply(null,u)}return i.createElement.apply(null,t)}p.displayName="MDXCreateElement"},7139:(e,n,t)=>{t.r(n),t.d(n,{assets:()=>l,contentTitle:()=>u,default:()=>d,frontMatter:()=>a,metadata:()=>o,toc:()=>s});var i=t(7462),r=(t(7294),t(3905));const a={sidebar_position:2},u="Navigation",o={unversionedId:"administration/navigation",id:"administration/navigation",title:"Navigation",description:"---",source:"@site/docs/administration/navigation.mdx",sourceDirName:"administration",slug:"/administration/navigation",permalink:"/intranetagglo/docs/administration/navigation",draft:!1,tags:[],version:"current",sidebarPosition:2,frontMatter:{sidebar_position:2},sidebar:"tutorialSidebar",previous:{title:"Introduction",permalink:"/intranetagglo/docs/administration/admin"},next:{title:"Alertes",permalink:"/intranetagglo/docs/administration/alerts"}},l={},s=[{value:"Pr\xe9sentation du menu de modification",id:"pr\xe9sentation-du-menu-de-modification",level:2},{value:"Ajouter un menu",id:"ajouter-un-menu",level:2},{value:"Modifier un menu",id:"modifier-un-menu",level:2},{value:"Supprimer un menu",id:"supprimer-un-menu",level:2},{value:"R\xe9organisation des menus",id:"r\xe9organisation-des-menus",level:2}],m={toc:s};function d(e){let{components:n,...a}=e;return(0,r.kt)("wrapper",(0,i.Z)({},m,a,{components:n,mdxType:"MDXLayout"}),(0,r.kt)("h1",{id:"navigation"},"Navigation"),(0,r.kt)("hr",null),(0,r.kt)("h2",{id:"pr\xe9sentation-du-menu-de-modification"},"Pr\xe9sentation du menu de modification"),(0,r.kt)("p",null,(0,r.kt)("img",{alt:"Capture du menu de Modification des menus",src:t(4559).Z,width:"698",height:"219"})),(0,r.kt)("p",null,"Un menu peut \xeatre de 3 type diff\xe9rent :"),(0,r.kt)("ul",null,(0,r.kt)("li",{parentName:"ul"},"Section"),(0,r.kt)("li",{parentName:"ul"},"Menu"),(0,r.kt)("li",{parentName:"ul"},"Sous-menu")),(0,r.kt)("p",null,"Chaque menu peut rediriger vers un lien ou fichier, ",(0,r.kt)("strong",{parentName:"p"},"\xe0 condition qu\u2019il ne poss\xe8de pas de menu \xab enfant \xbb"),"."),(0,r.kt)("p",null,"Seules les sections peuvent avoir des icones."),(0,r.kt)("p",null,"Le menu de modification se pr\xe9sente sous la forme de blocs, ici par exemple le bloc section \u2018Vie des assembl\xe9es\u2019 \xe0 pour menu \xab enfant \xbb : \xab R\xe8glement int\xe9rieur \xbb et \xab Elus \xbb \u2026"),(0,r.kt)("h2",{id:"ajouter-un-menu"},"Ajouter un menu"),(0,r.kt)("p",null,"Pour ajouter un menu il suffit de ",(0,r.kt)("strong",{parentName:"p"},"cliquer sur un bloc ",(0,r.kt)("inlineCode",{parentName:"strong"},"+"))," au niveau et emplacement souhait\xe9."),(0,r.kt)("p",null,"Cela ",(0,r.kt)("strong",{parentName:"p"},"ajoute un menu limit\xe9 au groupe ",(0,r.kt)("inlineCode",{parentName:"strong"},"admin"))," donc invisible pour les utilisateurs qui peut par la suite \xeatre modifier."),(0,r.kt)("h2",{id:"modifier-un-menu"},"Modifier un menu"),(0,r.kt)("p",null,"Pour modifier un menu, il faut cliquer sur le bouton."),(0,r.kt)("p",null,"Dans ce menu vous pouvez modifier : "),(0,r.kt)("ul",null,(0,r.kt)("li",{parentName:"ul"},(0,r.kt)("inlineCode",{parentName:"li"},"Titre du menu")," (attention si le titre est trop long le menu affichera des points de suspensions a la fin)"),(0,r.kt)("li",{parentName:"ul"},(0,r.kt)("inlineCode",{parentName:"li"},"Lien URL")," OU ",(0,r.kt)("inlineCode",{parentName:"li"},"redirection vers un fichier")," (une fois le fichier mis en ligne un lien local sera entrer dans le lien URL"),(0,r.kt)("li",{parentName:"ul"},(0,r.kt)("inlineCode",{parentName:"li"},"Groupes utilisateurs")," ",(0,r.kt)("a",{parentName:"li",href:"groups"},"(voir fonctionnement des groupes)"))),(0,r.kt)("h2",{id:"supprimer-un-menu"},"Supprimer un menu"),(0,r.kt)("p",null,"Il suffit de ",(0,r.kt)("strong",{parentName:"p"},"cliquer sur ",(0,r.kt)("img",{alt:"Capture du menu de Modification des menus",src:t(6423).Z,width:"16",height:"16"}))," dans le bloc menu."),(0,r.kt)("h2",{id:"r\xe9organisation-des-menus"},"R\xe9organisation des menus"),(0,r.kt)("p",null,"L\u2019ordre des menus peut \xeatre changer par un ",(0,r.kt)("strong",{parentName:"p"},"glisser-d\xe9poser")," en maintenant l\u2019ic\xf4ne ",(0,r.kt)("img",{alt:"icone de bouton glisser deposer",src:t(1875).Z,width:"16",height:"16"})," sur le menu souhait\xe9."),(0,r.kt)("p",null,"Les menus ne peuvent \xeatre r\xe9organiser que dans leur propre colonne, une section restera toujours une section, idem pour les menus et sous-menus."),(0,r.kt)("p",null,"Il est \xe9galement possible de d\xe9placer un menu de section et un sous-menu de menu, le menu d\xe9plac\xe9 sera positionn\xe9 en derni\xe8re position nouvelle section / menu."))}d.isMDXComponent=!0},4559:(e,n,t)=>{t.d(n,{Z:()=>i});const i=t.p+"assets/images/Image1-1acd59f615167bbca0556df938f0aa12.png"},1875:(e,n,t)=>{t.d(n,{Z:()=>i});const i="data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTYgMTYiIHdpZHRoPSIxZW0iIGhlaWdodD0iMWVtIiBmb2N1c2FibGU9ImZhbHNlIiByb2xlPSJpbWciIGFyaWEtbGFiZWw9ImFycm93cyBleHBhbmQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmktYXJyb3dzLWV4cGFuZCBteC1hdXRvIGItaWNvbiBiaSI+DQogICAgPGcgZGF0YS12LTQxYmU2NjMzPSIiPg0KICAgICAgICA8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xIDhhLjUuNSAwIDAgMSAuNS0uNWgxM2EuNS41IDAgMCAxIDAgMWgtMTNBLjUuNSAwIDAgMSAxIDh6TTcuNjQ2LjE0NmEuNS41IDAgMCAxIC43MDggMGwyIDJhLjUuNSAwIDAgMS0uNzA4LjcwOEw4LjUgMS43MDdWNS41YS41LjUgMCAwIDEtMSAwVjEuNzA3TDYuMzU0IDIuODU0YS41LjUgMCAxIDEtLjcwOC0uNzA4bDItMnpNOCAxMGEuNS41IDAgMCAxIC41LjV2My43OTNsMS4xNDYtMS4xNDdhLjUuNSAwIDAgMSAuNzA4LjcwOGwtMiAyYS41LjUgMCAwIDEtLjcwOCAwbC0yLTJhLjUuNSAwIDAgMSAuNzA4LS43MDhMNy41IDE0LjI5M1YxMC41QS41LjUgMCAwIDEgOCAxMHoiPjwvcGF0aD4NCiAgICA8L2c+DQo8L3N2Zz4="},6423:(e,n,t)=>{t.d(n,{Z:()=>i});const i="data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSIwIDAgMTYgMTYiIHdpZHRoPSIxZW0iIGhlaWdodD0iMWVtIiBmb2N1c2FibGU9ImZhbHNlIiByb2xlPSJpbWciIGFyaWEtbGFiZWw9InRyYXNoIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9ImN1cnJlbnRDb2xvciIgY2xhc3M9ImJpLXRyYXNoIG14LWF1dG8gYi1pY29uIGJpIj4NCiAgICA8ZyBkYXRhLXYtNDFiZTY2MzM9IiI+DQogICAgICAgIDxwYXRoIGQ9Ik01LjUgNS41QS41LjUgMCAwIDEgNiA2djZhLjUuNSAwIDAgMS0xIDBWNmEuNS41IDAgMCAxIC41LS41em0yLjUgMGEuNS41IDAgMCAxIC41LjV2NmEuNS41IDAgMCAxLTEgMFY2YS41LjUgMCAwIDEgLjUtLjV6bTMgLjVhLjUuNSAwIDAgMC0xIDB2NmEuNS41IDAgMCAwIDEgMFY2eiI+PC9wYXRoPg0KICAgICAgICA8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xNC41IDNhMSAxIDAgMCAxLTEgMUgxM3Y5YTIgMiAwIDAgMS0yIDJINWEyIDIgMCAwIDEtMi0yVjRoLS41YTEgMSAwIDAgMS0xLTFWMmExIDEgMCAwIDEgMS0xSDZhMSAxIDAgMCAxIDEtMWgyYTEgMSAwIDAgMSAxIDFoMy41YTEgMSAwIDAgMSAxIDF2MXpNNC4xMTggNCA0IDQuMDU5VjEzYTEgMSAwIDAgMCAxIDFoNmExIDEgMCAwIDAgMS0xVjQuMDU5TDExLjg4MiA0SDQuMTE4ek0yLjUgM1YyaDExdjFoLTExeiI+PC9wYXRoPg0KICAgIDwvZz4NCjwvc3ZnPg=="}}]);