<div id="flex-content">

@acfrepeater('flexible_content')

  @if(get_row_layout() == 'main_content')
    <section class="main-content">
      <div class="row align-center">
        <div class="medium-9 small-12 columns">
          <h1>{{ the_sub_field('heading') }}</h1>
          <img class="divider" src="{{ the_img('divider-dots.svg') }}">
          <div class="content">
            {{ the_sub_field('main_content') }}
          </div>
          @acfrepeater('cta_buttons')
          <a class="button" href="{{ the_sub_field('cta_button_url') }}" target="_blank">
            {{ the_sub_field('cta_button_text') }} <img class="arrow" src="{{ the_img('arrow.svg') }}">
          </a>
          @acfend
          @if (get_sub_field('view_map_button_text'))
            <div class="text-center">
              <a class="button" @click="showMap = !showMap">
                <span v-if="!showMap">{{ the_sub_field('view_map_button_text') }}</span>
                <span v-if="showMap">{{ the_sub_field('view_projects_button_text') }}</span>
              </a>
            </div>
          @endif
        </div>
      </div>
      <div class="tear-wrap">
        <div class="tear-gray"></div>
      </div>
    </section>

  @elseif(get_row_layout() == 'company_tiles')
    <section class="companies flex-template">
      {{-- Section Intro --}}
      <div class="companies-intro">
        <h3 class="subheading">{{ the_sub_field('section_heading') }}</h3>
        <img class="divider" src="{{ the_img('divider-dots.svg') }}">
        {{ the_sub_field('section_intro') }}
      </div>
      {{-- Grid Tiles --}}
      <div class="companies-grid">
        <div class="row expanded collapse">
          @define $tile_count = count(get_sub_field('company_tiles'))
          @acfrepeater('company_tiles')
          <div class="{{{ ($tile_count > 1) ? 'keiki-6' : '' }}} small-12 columns">
            <a class="grid-tile" href="{{ the_sub_field('link') }}" style="background-image:url({{ the_sub_field('background_image') }});">
              <div class="overlay">
                <div class="logo-wrap">
                  @if(get_sub_field('logo_or_title') === 'logo')
                    <img class="logo" src="{{ the_sub_field('logo') }}">
                  @elseif(get_sub_field('logo_or_title') === 'title')
                    <h4>{{ the_sub_field('title_under_logo') }}</h4>
                  @endif
                </div>
                <div class="content">
                  {{ the_sub_field('content') }}
                  <small>
            				Learn More
            				<img class="arrow" src="{{ the_img('arrow.svg') }}">
            			</small>
                </div>
              </div>
            </a>
          </div>
          @acfend
        </div>
      </div>
    </section>

  @elseif(get_row_layout() == 'two_half_width_images')
    <section class="two-half-width-images">
      <div class="row align-center">
        <div class="medium-9 small-12 columns">
          <div class="intro">
            @if (get_sub_field('section_heading_image'))
              <img class="heading-image" src="{{ the_sub_field('section_heading_image') }}">
            @else
              <h3 class="subheading">{{ the_sub_field('section_heading') }}</h3>
            @endif
            <img class="divider" src="{{ the_img('divider-dots.svg') }}">
            {{ the_sub_field('section_intro') }}
            @acfrepeater('cta_buttons')
              <a class="button" href="{{ the_sub_field('cta_button_link') }}">
                {{ the_sub_field('cta_button_text') }} <img class="arrow" src="{{ the_img('arrow.svg') }}">
              </a>
            @acfend
          </div>
        </div>
      </div>
      <div class="two-bg-images">
        <div class="left-image"  style="background-image:url({{ the_sub_field('left_half_background_image') }});"></div>
        <div class="right-image" style="background-image:url({{ the_sub_field('right_half_background_image') }});"></div>
      </div>
    </section>

  @elseif(get_row_layout() == 'projects')
    <section class="projects" v-if="!showMap">

      <div v-for="project in projects" class="project">
        <div class="featured-image" :style="'background-image:url(' + project.acf.featured_image + ');'"></div>
        <div class="row">
          <div class="medium-6 medium-offset-6 small-12 columns">
            <div class="content">
              <h3 v-text="project.post_title"></h3>
              <img class="divider" src="{{ the_img('divider-dots.svg') }}">
              <ul class="description-items">
                <li v-for="item in project.acf.project_description_line_items" class="description-item">
                  <span class="item-title" v-text="item.item_title"></span>
                  <span class="slash"> // </span>
                  <span v-text="item.item_entry"></span>
                </li>
              </ul>
              <a class="button visit-site" :href="project.acf.project_link_url" target="_blank">Visit Site<img class="arrow" src="{{ the_img('arrow.svg') }}">
              </a>
            </div>
          </div>
        </div>
      </div>

    </section>

    <ul v-if="!showMap && pages > 1" class="pagination" role="navigation" aria-label="Pagination">

      <li class="arrow previous" v-if="currentPage !== 1">
        <a @click.prevent="currentPage--; scrollTop();" aria-label="Previous page">
          <i class="ow-arrow-left-sm"></i>
          <span class="show-for-sr">Previous page</span>
        </a>
      </li>

      <li v-for="page in pages" :class="{ 'current': currentPage == page }">
        <a :aria-label="'Page ' + page" @click.prevent="currentPage = page; scrollTop()" v-text="page"></a>
      </li>

      <li class="arrow next" v-if="currentPage !== pages">
        <a @click.prevent="currentPage++; scrollTop();" aria-label="Next page">
          <i class="ow-arrow-left-sm"></i>
          <span class="show-for-sr">Next page</span>
        </a>
      </li>

    </ul>

  @endif
@acfend

  <section v-show="showMap" class="google-map" :class="{ 'show-map': showMap }">
    <div id="map"></div>
  </section>
</div>


<div class="reveal small" data-reveal data-v-offset="160" id="map-modal">
  <div class="map-modal-contain" v-if="Object.keys(project).length">
    <img :src="project.acf.featured_image" alt="featured image">
    <div class="info">
      <h2 v-text="project.post_title"></h2>
      <ul>
        <li v-for="item in project.acf.project_line_items">
          <span class="item-title" v-text="item.item_title"></span>
          <span class="item-entry" v-text="item.item_entry"></span>
        </li>
      </ul>
      <a class="button" :href="project.acf.project_link_url" target="_blank">
        LEARN MORE
      </a>
    </div>
  </div>
  <button class="close-button" data-close aria-label="Close reveal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@section('script')
<script async defer src="//maps.googleapis.com/maps/api/js?key=AIzaSyA30od0GaeXS-7aWHWUNaDnkpyQZbGoFZU">
</script>

@endsection
