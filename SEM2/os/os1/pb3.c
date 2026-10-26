#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int cmp(const void *a, const void *b) {
	return strcmp(*(char **)a, *(char **)b);
}


int main(){
	int N;
	printf("Introduceti nr de texte:\n");
	scanf("%d", &N);
	getchar();
	char **texte = (char **)malloc(N * sizeof(char *));
	printf("Introduceti %d texte de max 20 de caractere:\n", N);
	for (int i = 0; i < N; i++){
		texte[i] = (char *)malloc(21 * sizeof(char));
		fgets(texte[i], 21, stdin);
	}
	qsort(texte, N, sizeof(char *), cmp);

	printf("Textele sortate:\n");
	for (int i = 0; i < N; i++){
		printf("%s\n", texte[i]);
		free(texte[i]);
	}
	free(texte);
	return 0;
}
