document.addEventListener("DOMContentLoaded",()=>{r(),u(),m(),b()});function r(){const e=document.getElementById("darkToggle");e&&(localStorage.getItem("darkMode")==="enabled"&&(document.body.classList.add("dark-mode"),e.innerText="☀️"),e.addEventListener("click",()=>{document.body.classList.toggle("dark-mode"),document.body.classList.contains("dark-mode")?(localStorage.setItem("darkMode","enabled"),e.innerText="☀️"):(localStorage.setItem("darkMode","disabled"),e.innerText="🌙")}))}function u(){const e=document.getElementById("globalSearch"),s=document.getElementById("searchResults");!e||!s||(e.addEventListener("keyup",function(){let t=this.value.trim();if(t.length<2){s.style.display="none";return}fetch(`/tu/search-ajax?q=${t}`).then(o=>o.json()).then(o=>{let n="";o.siswa.length===0&&o.kelas.length===0&&(n=`
                        <div class="p-2 text-muted">
                            Tidak ada hasil...
                        </div>
                    `),o.siswa.length>0&&(n+=`
                        <div class="fw-bold px-2 pt-2">
                            Siswa
                        </div>
                    `,o.siswa.forEach(i=>{n+=`
                            <div
                                class="p-2 search-item"
                                onclick="window.location='/tu/siswa/${i.id}'"
                            >

                                <i class="bi bi-person me-2"></i>

                                ${i.nama}

                                <div class="small text-muted">
                                    ${i.nis}
                                </div>

                            </div>
                        `})),o.kelas.length>0&&(n+=`
                        <div class="fw-bold px-2 pt-2">
                            Kelas
                        </div>
                    `,o.kelas.forEach(i=>{n+=`
                            <div
                                class="p-2 search-item"
                                onclick="window.location='/tu/kelas/${i.id}'"
                            >

                                <i class="bi bi-people me-2"></i>

                                ${i.nama_kelas}

                            </div>
                        `})),s.innerHTML=n,s.style.display="block"})}),document.addEventListener("click",t=>{t.target.closest(".sidebar")||!e.contains(t.target)&&!s.contains(t.target)&&(s.style.display="none")}))}function m(){document.querySelectorAll(".submenu").forEach(s=>{const t=s.id,o=s.previousElementSibling?.querySelector(".dropdown-arrow");s.querySelector(".menu-item.active")&&(s.classList.add("show"),o&&o.classList.add("rotate")),localStorage.getItem(t)==="open"&&(s.classList.add("show"),o&&o.classList.add("rotate"))})}function b(){const e=document.getElementById("mobileSidebarToggle"),s=document.querySelector(".sidebar");if(!e||!s)return;const t=document.createElement("div");t.className="sidebar-overlay",document.body.appendChild(t);const o=()=>{document.body.classList.remove("mobile-sidebar-open"),e.setAttribute("aria-expanded","false"),e.setAttribute("aria-label","Buka menu")},n=()=>{document.body.classList.add("mobile-sidebar-open"),e.setAttribute("aria-expanded","true"),e.setAttribute("aria-label","Tutup menu")};e.addEventListener("click",()=>{document.body.classList.contains("mobile-sidebar-open")?o():n()}),t.addEventListener("click",o),document.addEventListener("click",a=>{if(window.innerWidth>768)return;const d=e.contains(a.target),l=s.contains(a.target);!d&&!l&&document.body.classList.contains("mobile-sidebar-open")&&o()});const i=()=>{window.innerWidth>768&&o()};window.addEventListener("resize",i),i()}window.showToast=function(e,s="success"){const t=document.getElementById("appToast"),o=t.querySelector(".toast-body");t.classList.remove("bg-success","bg-danger","text-white"),s==="success"&&t.classList.add("bg-success","text-white"),s==="error"&&t.classList.add("bg-danger","text-white"),o.innerText=e,new bootstrap.Toast(t,{delay:3e3}).show()};window.toggleMenu=function(e,s){const t=document.getElementById(e),o=document.getElementById(s);t.classList.contains("show")?(t.classList.remove("show"),o.classList.remove("rotate"),localStorage.removeItem(e)):(t.classList.add("show"),o.classList.add("rotate"),localStorage.setItem(e,"open"))};const c=document.getElementById("selectedCount");if(c){let s=function(){const t=document.querySelectorAll(".kelas-checkbox:checked").length;c.innerText=t+" kelas dipilih"};var g=s;document.querySelectorAll(".kelas-checkbox").forEach(t=>{t.addEventListener("change",s)})}"serviceWorker"in navigator&&window.addEventListener("load",()=>{navigator.serviceWorker.register("/sw.js").then(()=>{console.log("Service Worker berhasil didaftarkan")}).catch(e=>{console.error("Service Worker gagal:",e)})});
