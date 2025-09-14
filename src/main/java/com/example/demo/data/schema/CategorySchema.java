package com.example.demo.data.schema;

import com.example.demo.domain.entities.product.Category;
import jakarta.persistence.*;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.util.ArrayList;
import java.util.List;

@Entity
@Data // Generates getters, setters, toString, equals, and hashCode methods.
@NoArgsConstructor // Generates a no-args constructor.
@AllArgsConstructor // Generates a constructor with all arguments.
@Builder
@Table(name = "category")
public class CategorySchema {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    private String name;
    @OneToMany(mappedBy = "category")
    private List<ProductSchema> products;

    public CategorySchema(Category category) {
        this.id = category.getId();
        this.name = category.getName();
    }

    public Category toCategory() {
        return new Category(
                this.id,
                this.name,
                null
        );
    }
}
