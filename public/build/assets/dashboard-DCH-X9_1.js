document.addEventListener("DOMContentLoaded",()=>{c(),l(),d()});function c(){const s=document.getElementById("darkToggle");s&&(localStorage.getItem("darkMode")==="enabled"&&(document.body.classList.add("dark-mode"),s.innerText="☀️"),s.addEventListener("click",()=>{document.body.classList.toggle("dark-mode"),document.body.classList.contains("dark-mode")?(localStorage.setItem("darkMode","enabled"),s.innerText="☀️"):(localStorage.setItem("darkMode","disabled"),s.innerText="🌙")}))}function l(){const s=document.getElementById("globalSearch"),t=document.getElementById("searchResults");!s||!t||(s.addEventListener("keyup",function(){let e=this.value.trim();if(e.length<2){t.style.display="none";return}fetch(`/tu/search-ajax?q=${e}`).then(o=>o.json()).then(o=>{let a="";o.siswa.length===0&&o.kelas.length===0&&(a=`
                        <div class="p-2 text-muted">
                            Tidak ada hasil...
                        </div>
                    `),o.siswa.length>0&&(a+=`
                        <div class="fw-bold px-2 pt-2">
                            Siswa
                        </div>
                    `,o.siswa.forEach(n=>{a+=`
                            <div
                                class="p-2 search-item"
                                onclick="window.location='/tu/siswa/${n.id}'"
                            >

                                <i class="bi bi-person me-2"></i>

                                ${n.nama}

                                <div class="small text-muted">
                                    ${n.nis}
                                </div>

                            </div>
                        `})),o.kelas.length>0&&(a+=`
                        <div class="fw-bold px-2 pt-2">
                            Kelas
                        </div>
                    `,o.kelas.forEach(n=>{a+=`
                            <div
                                class="p-2 search-item"
                                onclick="window.location='/tu/kelas/${n.id}'"
                            >

                                <i class="bi bi-people me-2"></i>

                                ${n.nama_kelas}

                            </div>
                        `})),t.innerHTML=a,t.style.display="block"})}),document.addEventListener("click",e=>{e.target.closest(".sidebar")||!s.contains(e.target)&&!t.contains(e.target)&&(t.style.display="none")}))}function d(){document.querySelectorAll(".submenu").forEach(t=>{const e=t.id,o=t.previousElementSibling?.querySelector(".dropdown-arrow");t.querySelector(".menu-item.active")&&(t.classList.add("show"),o&&o.classList.add("rotate")),localStorage.getItem(e)==="open"&&(t.classList.add("show"),o&&o.classList.add("rotate"))})}window.showToast=function(s,t="success"){const e=document.getElementById("appToast"),o=e.querySelector(".toast-body");e.classList.remove("bg-success","bg-danger","text-white"),t==="success"&&e.classList.add("bg-success","text-white"),t==="error"&&e.classList.add("bg-danger","text-white"),o.innerText=s,new bootstrap.Toast(e,{delay:3e3}).show()};window.toggleMenu=function(s,t){const e=document.getElementById(s),o=document.getElementById(t);e.classList.contains("show")?(e.classList.remove("show"),o.classList.remove("rotate"),localStorage.removeItem(s)):(e.classList.add("show"),o.classList.add("rotate"),localStorage.setItem(s,"open"))};const i=document.getElementById("selectedCount");if(i){let t=function(){const e=document.querySelectorAll(".kelas-checkbox:checked").length;i.innerText=e+" kelas dipilih"};var r=t;document.querySelectorAll(".kelas-checkbox").forEach(e=>{e.addEventListener("change",t)})}"serviceWorker"in navigator&&window.addEventListener("load",()=>{navigator.serviceWorker.register("/sw.js").then(()=>{console.log("Service Worker berhasil didaftarkan")}).catch(s=>{console.error("Service Worker gagal:",s)})});
