<script setup lang="ts">
import { ref } from 'vue';
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();

const expanded = ref<string[]>([]);

function toggle(itemTitle: string) {
  if (expanded.value.includes(itemTitle)) {
    expanded.value = expanded.value.filter(i => i !== itemTitle);
  } else {
    expanded.value.push(itemTitle);
  }
}

function isActive(href: string | undefined) {
  if (!href) return false;
  return page.url === href;
}
</script>

<template>
  <SidebarGroup class="px-2 py-0">
    <SidebarGroupLabel>Platform</SidebarGroupLabel>
    <SidebarMenu>
      <template v-for="item in items" :key="item.title">
        <SidebarMenuItem v-if="!item.children" >
          <SidebarMenuButton
            as-child
            :is-active="isActive(item.href)"
            :tooltip="item.title"
          >
            <Link :href="item.href">
              <component :is="item.icon" />
              <span>{{ item.title }}</span>
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>

        <SidebarMenuItem v-else>
          <SidebarMenuButton
            @click="toggle(item.title)"
            :is-active="isActive(item.href) || expanded.includes(item.title)"
            class="flex justify-between items-center cursor-pointer select-none"
            :tooltip="item.title"
          >
            <div class="flex items-center space-x-2">
              <component :is="item.icon" />
              <span>{{ item.title }}</span>
            </div>
            <svg
              class="w-4 h-4 transition-transform duration-200"
              :class="{ 'rotate-90': expanded.includes(item.title) }"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
          </SidebarMenuButton>

          <SidebarMenu v-show="expanded.includes(item.title)" class="ml-6 mt-1 space-y-1">
            <SidebarMenuItem v-for="child in item.children" :key="child.title">
              <SidebarMenuButton as-child :is-active="isActive(child.href)" :tooltip="child.title">
                <Link :href="child.href">
                  <span>{{ child.title }}</span>
                </Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarMenuItem>
      </template>
    </SidebarMenu>
  </SidebarGroup>
</template>
